@extends('layouts.admin')

@section('title', 'Add Mapping')

@section('content_header')
    <x-adminlte-content-header 
        title="Add Employee Mapping"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Devices', 'url' => route('zkteco.devices.index'),
            ['label' => 'Employee Mappings', 'url' => route('zkteco.devices.mappings.index', $device)],
            ['label' => 'Add Mapping']]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Employee Mapping - {{ $device->serial_number }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('zkteco.devices.mappings.store', $device) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="device_pin" class="form-label">Device PIN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('device_pin') is-invalid @enderror" id="device_pin" name="device_pin" value="{{ old('device_pin') }}" required>
                            @error('device_pin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="employee_code" class="form-label">Employee Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('employee_code') is-invalid @enderror" id="employee_code" name="employee_code" value="{{ old('employee_code') }}" required>
                            @error('employee_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Create Mapping
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
