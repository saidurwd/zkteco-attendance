@extends('layouts.admin')

@section('title', 'Add Device')

@section('content')
    <h1>Add ZKTeco Device</h1>

    <form method="POST" action="{{ route('zkteco.devices.store') }}">
        @csrf

        <div>
            <label>Serial Number *</label><br>
            <input type="text" name="serial_number" value="{{ old('serial_number') }}" required>
            @error('serial_number') <div style="color: red;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Device Name</label><br>
            <input type="text" name="device_name" value="{{ old('device_name') }}">
        </div>

        <div>
            <label>Model</label><br>
            <input type="text" name="model" value="{{ old('model') }}">
        </div>

        <div>
            <label>Firmware Version</label><br>
            <input type="text" name="firmware_version" value="{{ old('firmware_version') }}">
        </div>

        <div>
            <label>Site Code</label><br>
            <input type="text" name="site_code" value="{{ old('site_code') }}">
        </div>

        <div>
            <label>Location</label><br>
            <input type="text" name="location" value="{{ old('location') }}">
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                Active
            </label>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit">Create Device</button>
            <a href="{{ route('zkteco.devices.index') }}">Cancel</a>
        </div>
    </form>
@stop