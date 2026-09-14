@extends('layouts.admin')

@section('title', 'Edit Mapping')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Employee Mapping - {{ $device->serial_number }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('zkteco.devices.mappings.update', [$device, $mapping]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="device_pin" class="form-label">Device PIN</label>
                            <input type="text" class="form-control" id="device_pin" value="{{ $mapping->device_pin }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="employee_code" class="form-label">Employee Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('employee_code') is-invalid @enderror" id="employee_code" name="employee_code" value="{{ old('employee_code', $mapping->employee_code) }}" required>
                            @error('employee_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $mapping->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Mapping
                            </button>
                            <a href="{{ route('zkteco.devices.mappings.index', $device) }}" class="btn btn-secondary">
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
