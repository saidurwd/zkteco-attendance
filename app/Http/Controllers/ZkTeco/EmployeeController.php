<?php

namespace App\Http\Controllers\ZkTeco;

use App\Http\Controllers\Controller;
use App\Models\ZkEmployee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = ZkEmployee::query()
            ->when($request->search, fn ($q, $search) => $q->where('employee_id', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('department', 'like', "%{$search}%"))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('zkteco.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('zkteco.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:100|unique:zk_employees,employee_id',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'site_code' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        ZkEmployee::create($validated);

        return redirect()->route('zkteco.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function edit(ZkEmployee $employee)
    {
        return view('zkteco.employees.edit', compact('employee'));
    }

    public function update(Request $request, ZkEmployee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'site_code' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $employee->update($validated);

        return redirect()->route('zkteco.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(ZkEmployee $employee)
    {
        $employee->delete();

        return redirect()->route('zkteco.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
