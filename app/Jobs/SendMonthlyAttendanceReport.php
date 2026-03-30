<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendMonthlyAttendanceReport implements ShouldQueue
{
    use Queueable;

    protected $month;
    protected $year;

    /**
     * Create a new job instance.
     */
    public function __construct($month = null, $year = null)
    {
        $this->month = $month ?? now()->subMonth()->month;
        $this->year = $year ?? now()->subMonth()->year;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting monthly attendance report job for {$this->month}/{$this->year}");

        $employees = Employee::where('is_active', true)->with('user')->get();

        foreach ($employees as $employee) {
            try {
                $this->sendEmployeeReport($employee);
                Log::info("Sent monthly report to employee: {$employee->name} ({$employee->user->email})");
            } catch (\Exception $e) {
                Log::error("Failed to send monthly report to employee {$employee->name}: " . $e->getMessage());
            }
        }

        Log::info("Completed monthly attendance report job for {$this->month}/{$this->year}");
    }

    /**
     * Send monthly attendance report to a specific employee
     */
    protected function sendEmployeeReport(Employee $employee)
    {
        $startDate = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endDate = Carbon::create($this->year, $this->month, 1)->endOfMonth();

        // Get attendance data for the employee in the specified month
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date')
            ->get();

        // Calculate statistics
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $presentCount = $attendances->where('status', 'present')->count();
        $lateCount = $attendances->where('status', 'late')->count();
        $absentCount = $totalDays - $attendances->count();

        $presentPercentage = $totalDays > 0 ? round(($presentCount / $totalDays) * 100, 2) : 0;
        $latePercentage = $totalDays > 0 ? round(($lateCount / $totalDays) * 100, 2) : 0;
        $absentPercentage = $totalDays > 0 ? round(($absentCount / $totalDays) * 100, 2) : 0;

        // Prepare data for email
        $reportData = [
            'employee' => $employee,
            'month' => $startDate->format('F Y'),
            'total_days' => $totalDays,
            'present_count' => $presentCount,
            'late_count' => $lateCount,
            'absent_count' => $absentCount,
            'present_percentage' => $presentPercentage,
            'late_percentage' => $latePercentage,
            'absent_percentage' => $absentPercentage,
            'attendances' => $attendances,
            'performance_status' => $this->getPerformanceStatus($presentPercentage),
        ];

        // Send email
        Mail::to($employee->user->email)->send(new \App\Mail\MonthlyAttendanceReport($reportData));
    }

    /**
     * Get performance status based on attendance percentage
     */
    protected function getPerformanceStatus($presentPercentage)
    {
        if ($presentPercentage >= 90) {
            return ['status' => 'Excellent', 'color' => 'green', 'message' => 'Kehadiran Anda sangat baik!'];
        } elseif ($presentPercentage >= 80) {
            return ['status' => 'Good', 'color' => 'blue', 'message' => 'Kehadiran Anda baik, pertahankan!'];
        } elseif ($presentPercentage >= 70) {
            return ['status' => 'Fair', 'color' => 'yellow', 'message' => 'Kehadiran Anda cukup baik, tingkatkan lagi!'];
        } else {
            return ['status' => 'Poor', 'color' => 'red', 'message' => 'Kehadiran Anda perlu ditingkatkan!'];
        }
    }
}
