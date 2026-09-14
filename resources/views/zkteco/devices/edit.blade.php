@extends('layouts.admin')

@section('title', 'Edit Device')

@section('content')
    <h1>Edit ZKTeco Device</h1>

    <form method="POST" action="{{ route('zkteco.devices.update', $device) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Serial Number</label><br>
            <input type="text" value="{{ $device->serial_number }}" disabled>
        </div>

        <div>
            <label>Device Name</label><br>
            <input type="text" name="device_name" value="{{ old('device_name', $device->device_name) }}">
        </div>

        <div>
            <label>Model</label><br>
            <input type="text" name="model" value="{{ old('model', $device->model) }}">
        </div>

        <div>
            <label>Firmware Version</label><br>
            <input type="text" name="firmware_version" value="{{ old('firmware_version', $device->firmware_version) }}">
        </div>

        <div>
            <label>Site Code</label><br>
            <input type="text" name="site_code" value="{{ old('site_code', $device->site_code) }}">
        </div>

        <div>
            <label>Location</label><br>
            <input type="text" name="location" value="{{ old('location', $device->location) }}">
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $device->is_active) ? 'checked' : '' }}>
                Active
            </label>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit">Update Device</button>
            <a href="{{ route('zkteco.devices.index') }}">Cancel</a>
        </div>
    </form>
@stop