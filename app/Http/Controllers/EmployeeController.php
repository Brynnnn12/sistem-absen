<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Services\EmployeeService;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class EmployeeController extends Controller
{

    public function __construct(protected EmployeeService $employeeService)
    {
    }

    #[Authorize('viewAny', Employee::class)]
    public function index()
    {
        $employees = $this->employeeService->getEmployeesPaginated(10);

        return view('dashboard.karyawan.index', compact('employees'));
    }

    #[Authorize('create', Employee::class)]
    public function create()
    {
        $roles = $this->employeeService->getAllRoles();
        return view('dashboard.karyawan.create', compact('roles'));
    }

    #[Authorize('create', Employee::class)]
    public function store(StoreEmployeeRequest $request)
    {
        // dd($request->validated());
        $this->employeeService->createEmployee($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Karyawan berhasil dibuat.');
    }

    #[Authorize('view', 'employee')]
    public function show(Employee $employee)
    {

        return view('dashboard.karyawan.show', compact('employee'));
    }

    #[Authorize('update', 'employee')]
    public function edit(Employee $employee)
    {

        $roles = $this->employeeService->getAllRoles();

        return view('dashboard.karyawan.edit', compact('employee', 'roles'));
    }

    #[Authorize('update', 'employee')]
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        // dd($request->validated());
        $this->employeeService->updateEmployee($employee, $request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Karyawan berhasil diperbarui.');
    }

    #[Authorize('delete', 'employee')]
    public function destroy(Employee $employee)
    {
        $this->employeeService->deleteEmployee($employee);

        return redirect()->route('employees.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}
