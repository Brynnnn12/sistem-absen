<?php

namespace App\Http\Services;

use App\Models\Attendance;
use App\Models\User;
use App\Http\Repositories\AttendanceRepository;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function __construct(protected AttendanceRepository $attendanceRepository)
    {
    }

    public function getAttendancesPaginated(int $perPage = 10)
    {
        return $this->attendanceRepository->getAllPaginated($perPage);
    }

    public function createAttendance(array $data): Attendance
    {
        return $this->attendanceRepository->create($data);
    }

    public function updateAttendance(Attendance $attendance, array $data): bool
    {
        return $this->attendanceRepository->update($attendance, $data);
    }

    public function deleteAttendance(Attendance $attendance): bool
    {
        return $this->attendanceRepository->delete($attendance);
    }

    public function checkIn(User $user): array
    {
        $employee = $user->employee;

        if (!$employee) {
            return ['success' => false, 'message' => 'Data karyawan tidak ditemukan.'];
        }

        $now = now();
        if ($now->format('H:i') > '07:00') {
            return ['success' => false, 'message' => 'Waktu check-in sudah berakhir (maksimal pukul 07:00).'];
        }

        $today = $now->toDateString();
        $existing = $this->attendanceRepository->findByEmployeeAndDate($employee->id, $today);

        if ($existing) {
            return ['success' => false, 'message' => 'Anda sudah check-in hari ini.'];
        }

        $checkInTime = $now;
        $status = $checkInTime->format('H:i') <= '08:00' ? 'present' : 'late';

        $this->attendanceRepository->create([
            'employee_id' => $employee->id,
            'date' => $today,
            'check_in' => $checkInTime->format('H:i:s'),
            'status' => $status,
        ]);

        return ['success' => true, 'message' => 'Check-in berhasil pada ' . $checkInTime->format('H:i') . '.', 'time' => $checkInTime->format('H:i')];
    }

    public function checkOut(User $user): array
    {
        $employee = $user->employee;

        if (!$employee) {
            return ['success' => false, 'message' => 'Data karyawan tidak ditemukan.'];
        }

        $now = now();
        $currentTime = $now->format('H:i');
        if ($currentTime < '15:00' || $currentTime > '16:00') {
            return ['success' => false, 'message' => 'Check-out hanya bisa dilakukan antara pukul 15:00 - 16:00.'];
        }

        $today = $now->toDateString();
        $attendance = $this->attendanceRepository->findByEmployeeAndDate($employee->id, $today);

        if (!$attendance) {
            return ['success' => false, 'message' => 'Anda belum check-in hari ini.'];
        }

        if ($attendance->check_out) {
            return ['success' => false, 'message' => 'Anda sudah check-out hari ini.'];
        }

        $checkOutTime = $now;
        $this->attendanceRepository->update($attendance, ['check_out' => $checkOutTime->format('H:i:s')]);

        return ['success' => true, 'message' => 'Check-out berhasil pada ' . $checkOutTime->format('H:i') . '.', 'time' => $checkOutTime->format('H:i')];
    }

    public function getTodayAttendance(User $user): ?Attendance
    {
        $employee = $user->employee;
        if (!$employee) {
            return null;
        }

        $today = now()->toDateString();
        return $this->attendanceRepository->findByEmployeeAndDate($employee->id, $today);
    }

    public function getRecentAttendances(User $user, int $days = 30)
    {
        $employee = $user->employee;
        if (!$employee) {
            return collect();
        }

        return $this->attendanceRepository->getByEmployeeAndDateRange($employee->id, now()->subDays($days)->toDateString(), now()->toDateString());
    }

    public function getAttendancesByEmployee(string $employeeId, int $perPage = 10)
    {
        return $this->attendanceRepository->getByEmployeePaginated($employeeId, $perPage);
    }
}
