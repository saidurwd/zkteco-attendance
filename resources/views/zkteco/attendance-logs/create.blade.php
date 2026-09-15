@extends('layouts.admin')

@section('title', 'Add Attendance Log')

@section('content_header')
    <x-adminlte-content-header 
        title="Add Attendance Log"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Attendance Logs', 'url' => route('zkteco.attendance-logs.index'),
            ['label' => 'Add Attendance Log']]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Attendance Log</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('zkteco.attendance-logs.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="serial_number" class="form-label">Serial Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('serial_number') is-invalid @enderror" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" required>
                            @error('serial_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="employee_pin" class="form-label">Employee PIN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('employee_pin') is-invalid @enderror" id="employee_pin" name="employee_pin" value="{{ old('employee_pin') }}" required>
                            @error('employee_pin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="attendance_time" class="form-label">Attendance Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('attendance_time') is-invalid @enderror" id="attendance_time" name="attendance_time" value="{{ old('attendance_time') }}" required>
                            @error('attendance_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <input type="number" class="form-control @error('status') is-invalid @enderror" id="status" name="status" value="{{ old('status') }}">
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="verify_type" class="form-label">Verify Type</label>
                            <input type="number" class="form-control @error('verify_type') is-invalid @enderror" id="verify_type" name="verify_type" value="{{ old('verify_type') }}">
                            @error('verify_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="work_code" class="form-label">Work Code</label>
                            <input type="number" class="form-control @error('work_code') is-invalid @enderror" id="work_code" name="work_code" value="{{ old('work_code') }}">
                            @error('work_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reserved_1" class="form-label">Reserved 1</label>
                            <input type="text" class="form-control @error('reserved_1') is-invalid @enderror" id="reserved_1" name="reserved_1" value="{{ old('reserved_1') }}">
                            @error('reserved_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reserved_2" class="form-label">Reserved 2</label>
                            <input type="text" class="form-control @error('reserved_2') is-invalid @enderror" id="reserved_2" name="reserved_2" value="{{ old('reserved_2') }}">
                            @error('reserved_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="raw_data" class="form-label">Raw Data</label>
                            <textarea class="form-control @error('raw_data') is-invalid @enderror" id="raw_data" name="raw_data" rows="3">{{ old('raw_data') }}</textarea>
                            @error('raw_data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="source" class="form-label">Source</label>
                            <input type="text" class="form-control @error('source') is-invalid @enderror" id="source" name="source" value="{{ old('source', 'zkteco_push') }}">
                            @error('source')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Create Log
                            </button>
                            <a href="{{ route('zkteco.attendance-logs.index') }}" class="btn btn-secondary">
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
