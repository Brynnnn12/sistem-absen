<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Http\Services\AttendanceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Attributes\Controllers\Authorize;


class AttendanceController extends Controller
{

    public function __construct(protected AttendanceService $attendanceService)
    {
    }

    #[Authorize('viewAny', Attendance::class)]
    public function index()
    {
        $user = Auth::user();

        // Admin melihat semua, Karyawan melihat miliknya sendiri
        if ($user->hasRole('admin')) {
            $attendances = $this->attendanceService->getAttendancesPaginated(10);
        } else {
            $employeeId = $user->employee?->id;

            if (!$employeeId) {
                return redirect()->route('dashboard')->with('error', 'Profil karyawan tidak ditemukan.');
            }

            $attendances = $this->attendanceService->getAttendancesByEmployee($employeeId, 10);
        }

        return view('dashboard.absen.index', compact('attendances'));
    }

    #[Authorize('create', Attendance::class)]
    public function create()
    {
        $employees = Employee::where('is_active', true)->select('id', 'name', 'nik')->get();
        return view('dashboard.absen.create', compact('employees'));
    }

    #[Authorize('create', Attendance::class)]
    public function store(StoreAttendanceRequest $request)
    {
        $this->attendanceService->createAttendance($request->validated());

        return redirect()->route('attendances.index')
            ->with('success', 'Absensi berhasil dibuat.');
    }

    #[Authorize('view', 'attendance')]
    public function show(Attendance $attendance)
    {
        return view('dashboard.absen.show', compact('attendance'));
    }

    #[Authorize('update', 'attendance')]
    public function edit(Attendance $attendance)
    {
        $employees = Employee::where('is_active', true)->select('id', 'name', 'nik')->get();
        return view('dashboard.absen.edit', compact('attendance', 'employees'));
    }

    #[Authorize('update', 'attendance')]
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        $this->attendanceService->updateAttendance($attendance, $request->validated());

        return redirect()->route('attendances.index')
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    #[Authorize('delete', 'attendance')]
    public function destroy(Attendance $attendance)
    {
        $this->attendanceService->deleteAttendance($attendance);

        return redirect()->route('attendances.index')
            ->with('success', 'Absensi berhasil dihapus.');
    }

    public function checkIn()
    {
        $result = $this->attendanceService->checkIn(Auth::user());
        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function checkOut()
    {
        $result = $this->attendanceService->checkOut(Auth::user());
        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function report()
    {
        // Proteksi tambahan untuk Admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Akses ditolak.');
        }

        $startDate = request('start_date', now()->startOfMonth()->toDateString());
        $endDate = request('end_date', now()->endOfMonth()->toDateString());
        $employeeId = request('employee_id');

        $reportData = $this->attendanceService->generateAttendanceReport($startDate, $endDate, $employeeId);

        return view('dashboard.reports.attendance', compact('reportData', 'startDate', 'endDate', 'employeeId'));
    }

    public function exportReportPDF()
    {
        // Proteksi tambahan untuk Admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Akses ditolak.');
        }

        $startDate = request('start_date', now()->startOfMonth()->toDateString());
        $endDate = request('end_date', now()->endOfMonth()->toDateString());
        $employeeId = request('employee_id');

        $reportData = $this->attendanceService->generateAttendanceReport($startDate, $endDate, $employeeId);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dashboard.reports.attendance-pdf', compact('reportData', 'startDate', 'endDate', 'employeeId'));

        $filename = 'laporan-absensi-' . \Carbon\Carbon::parse($startDate)->format('d-m-Y') . '-sd-' . \Carbon\Carbon::parse($endDate)->format('d-m-Y') . '.pdf';

        return $pdf->download($filename);
    }

    public function monitoring()
    {
        // Proteksi tambahan untuk Admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Akses ditolak.');
        }

        // Get system monitoring data
        $totalEmployees = \App\Models\Employee::count();
        $activeEmployees = \App\Models\Employee::where('is_active', true)->count();
        $totalAttendances = \App\Models\Attendance::count();
        $todayAttendances = \App\Models\Attendance::whereDate('date', today())->count();

        // Recent attendances (last 7 days)
        $recentAttendances = \App\Models\Attendance::with('employee')
            ->where('date', '>=', now()->subDays(7))
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->limit(20)
            ->get();

        // Attendance statistics for current month
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyStats = \App\Models\Attendance::selectRaw('
                COUNT(*) as total_records,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_count,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_count
            ')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->first();

        return view('dashboard.monitoring.index', compact(
            'totalEmployees',
            'activeEmployees',
            'totalAttendances',
            'todayAttendances',
            'recentAttendances',
            'monthlyStats'
        ));
    }
}
