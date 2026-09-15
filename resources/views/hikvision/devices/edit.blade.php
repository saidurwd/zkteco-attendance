@extends('layouts.admin')

@section('title', 'Edit Hikvision Device')

@section('content_header')
    <x-adminlte-content-header
        title="Edit Hikvision Device"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Hikvision Devices', 'url' => route('hikvision.devices.index')],
            ['label' => 'Edit Device']]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Hikvision Device</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('hikvision.devices.update', $device) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="device_name" class="form-label">Device Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('device_name') is-invalid @enderror" id="device_name" name="device_name" value="{{ old('device_name', $device->device_name) }}" required>
                            @error('device_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="device_serial" class="form-label">Device Serial</label>
                            <input type="text" class="form-control @error('device_serial') is-invalid @enderror" id="device_serial" name="device_serial" value="{{ old('device_serial', $device->device_serial) }}">
                            @error('device_serial')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="device_model" class="form-label">Device Model</label>
                            <input type="text" class="form-control @error('device_model') is-invalid @enderror" id="device_model" name="device_model" value="{{ old('device_model', $device->device_model) }}">
                            @error('device_model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ip_address" class="form-label">IP Address</label>
                            <input type="text" class="form-control @error('ip_address') is-invalid @enderror" id="ip_address" name="ip_address" value="{{ old('ip_address', $device->ip_address) }}">
                            @error('ip_address')
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

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $device->username) }}">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_encrypted" class="form-label">Password (Encrypted)</label>
                            <input type="text" class="form-control @error('password_encrypted') is-invalid @enderror" id="password_encrypted" name="password_encrypted" value="{{ old('password_encrypted', $device->password_encrypted) }}">
                            @error('password_encrypted')
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
                            <a href="{{ route('hikvision.devices.index') }}" class="btn btn-secondary">
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
