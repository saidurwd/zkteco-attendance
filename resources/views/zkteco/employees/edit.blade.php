@extends('layouts.admin')

@section('title', 'Edit Employee')

@section('content_header')
    <x-adminlte-content-header 
        title="Edit Employee"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Employees', 'url' => route('zkteco.employees.index')],
            ['label' => 'Edit Employee']]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Employee</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('zkteco.employees.update', $employee) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee ID</label>
                            <input type="text" class="form-control" id="employee_id" value="{{ $employee->employee_id }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $employee->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $employee->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ old('department', $employee->department) }}">
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $employee->position) }}">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="site_code" class="form-label">Site Code</label>
                            <input type="text" class="form-control @error('site_code') is-invalid @enderror" id="site_code" name="site_code" value="{{ old('site_code', $employee->site_code) }}">
                            @error('site_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Employee
                            </button>
                            <a href="{{ route('zkteco.employees.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@stop
