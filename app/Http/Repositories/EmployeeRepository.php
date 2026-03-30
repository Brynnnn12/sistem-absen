<?php


namespace App\Http\Repositories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmployeeRepository
{
    public function __construct(protected Employee $employee)
    {
    }

    /**
     * Menggunakan latest() agar data terbaru di atas
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->employee->with(['user'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * ID diganti string karena menggunakan UUID
     */
    public function findById(string $id): ?Employee
    {
        return $this->employee->find($id);
    }

    public function findByIdWithRelationships(string $id, array $relationships = []): ?Employee
    {
        return $this->employee->with($relationships)->findOrFail($id);
    }

    public function create(array $data): Employee
    {
        return $this->employee->create($data);
    }

    public function update(Employee $employee, array $data): bool
    {
        return $employee->update($data);
    }

    public function delete(Employee $employee): bool
    {
        return $employee->delete();
    }

    /**
     * Pencarian cerdas: Mencari nama di tabel employee
     * ATAU mencari email di tabel user yang berelasi
     */
    public function search(string $query): Collection
    {
        return $this->employee->where('name', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('email', 'like', "%{$query}%");
            })
            ->get();
    }

    public function countActive(): int
    {
        return $this->employee->where('is_active', true)->count();
    }
}
