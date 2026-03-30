<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Http\Services\AttendanceService;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $attendanceService)
    {
    }

    #[Authorize('viewAny', Attendance::class)]
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // Admin melihat semua data
            $attendances = $this->attendanceService->getAttendancesPaginated(10);
        } else {
            // Karyawan hanya melihat data absensinya sendiri
            $attendances = $this->attendanceService->getAttendancesByEmployee($user->employee->id, 10);
        }

        return view('dashboard.absen.index', compact('attendances'));
    }

    #[Authorize('create', Attendance::class)]
    public function create()
    {
        $employees = \App\Models\Employee::where('is_active', true)->select('id', 'name', 'nik')->get();
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
        $employees = \App\Models\Employee::where('is_active', true)->select('id', 'name', 'nik')->get();
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

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    public function checkOut()
    {
        $result = $this->attendanceService->checkOut(Auth::user());

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
}
