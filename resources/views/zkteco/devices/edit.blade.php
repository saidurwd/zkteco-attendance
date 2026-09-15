@extends('layouts.admin')

@section('title', 'Edit Device')

@section('content_header')
    <x-adminlte-content-header 
        title="Edit Device"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Devices', 'url' => route('zkteco.devices.index'),
            ['label' => 'Edit Device']]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit ZKTeco Device</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('zkteco.devices.update', $device) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number" value="{{ $device->serial_number }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="device_name" class="form-label">Device Name</label>
                            <input type="text" class="form-control @error('device_name') is-invalid @enderror" id="device_name" name="device_name" value="{{ old('device_name', $device->device_name) }}">
                            @error('device_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model', $device->model) }}">
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="firmware_version" class="form-label">Firmware Version</label>
                            <input type="text" class="form-control @error('firmware_version') is-invalid @enderror" id="firmware_version" name="firmware_version" value="{{ old('firmware_version', $device->firmware_version) }}">
                            @error('firmware_version')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="site_code" class="form-label">Site Code</label>
                            <input type="text" class="form-control @error('site_code') is-invalid @enderror" id="site_code" name="site_code" value="{{ old('site_code', $device->site_code) }}">
                            @error('site_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $device->location) }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $device->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Device
                            </button>
                            <a href="{{ route('zkteco.devices.index') }}" class="btn btn-secondary">
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
