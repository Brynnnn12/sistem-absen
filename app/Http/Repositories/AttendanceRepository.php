<?php

namespace App\Http\Repositories;

use App\Models\Attendance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class AttendanceRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected Attendance $attendance)
    {
    }

    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->attendance->with(['employee.user'])
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Attendance
    {
        return $this->attendance->create($data);
    }


    public function update(Attendance $attendance, array $data): bool
    {
        return $attendance->update($data);
    }

    public function delete(Attendance $attendance): bool
    {
        return $attendance->delete();
    }

    public function findByEmployeeAndDate(string $employeeId, string $date): ?Attendance
    {
        return $this->attendance->where('employee_id', $employeeId)->where('date', $date)->first();
    }

    public function getByEmployeeAndDateRange(string $employeeId, string $startDate, string $endDate)
    {
        return $this->attendance->where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getByEmployeePaginated(string $employeeId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->attendance->with(['employee.user'])
            ->where('employee_id', $employeeId)
            ->latest()
            ->paginate($perPage);
    }
}
