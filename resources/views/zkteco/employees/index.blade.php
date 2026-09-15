@extends('layouts.admin')

@section('title', 'Employees')

@section('content_header')
    <x-adminlte-content-header 
        title="Employees"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Employees']]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Employees</h3>
                    <div class="card-tools">
                        <a href="{{ route('zkteco.employees.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add Employee
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" action="{{ route('zkteco.employees.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search employees..." value="{{ request('search') }}">
                            <button class="btn btn-default" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Site</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->employee_id }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>{{ $employee->department }}</td>
                                        <td>{{ $employee->position }}</td>
                                        <td>{{ $employee->site_code }}</td>
                                        <td>
                                            @if ($employee->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('zkteco.employees.edit', $employee) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('zkteco.employees.destroy', $employee) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No employees found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

                    @if ($employees->hasPages())
                        <div class="mt-3">
                            {{ $employees->links() }}
                        </div>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@stop
