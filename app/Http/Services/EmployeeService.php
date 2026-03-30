<?php

namespace App\Http\Services;

use App\Models\Employee;
use App\Models\User;
use App\Http\Repositories\EmployeeRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; // Import Spatie Role

class EmployeeService
{
    public function __construct(protected EmployeeRepository $employeeRepository)
    {}


    public function getAllRoles()
    {
        return Role::pluck('name', 'id');
    }


    public function getEmployeeForEdit(Employee $employee): Employee
    {
        return $employee->load('user');
    }

    /**
     * Selebihnya adalah method yang sudah Anda buat sebelumnya...
     */
    public function getEmployeesPaginated(int $perPage = 10)
    {
        return $this->employeeRepository->getAllPaginated($perPage);
    }

    public function createEmployee(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $role = Role::findById($data['role_id']);
            $user->assignRole($role);

            return $this->employeeRepository->create([
                'user_id'   => $user->id,
                'nik'       => $data['nik'],
                'name'      => $data['name'],
                'phone'     => $data['phone'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);
        });
    }

    public function updateEmployee(Employee $employee, array $data): bool
    {
        return DB::transaction(function () use ($employee, $data) {
            $userData = array_filter([
                'name'  => $data['name'] ?? null,
                'email' => $data['email'] ?? null,
            ]);

            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            $employee->user->update($userData);

            if (isset($data['role_id'])) {
                $role = Role::findById($data['role_id']);
                $employee->user->syncRoles($role);
            }

            return $this->employeeRepository->update($employee, [
                'nik'       => $data['nik'] ?? $employee->nik,
                'name'      => $data['name'] ?? $employee->name,
                'phone'     => $data['phone'] ?? $employee->phone,
                'is_active' => $data['is_active'] ?? $employee->is_active,
            ]);
        });
    }

    public function deleteEmployee(Employee $employee): bool
    {
        return DB::transaction(function () use ($employee) {
            $user = $employee->user;
            $this->employeeRepository->delete($employee);
            return $user->delete();
        });
    }
}
