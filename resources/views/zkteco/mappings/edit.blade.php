<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mapping</title>
</head>
<body>
    <h1>Edit Employee Mapping - {{ $device->serial_number }}</h1>

    <form method="POST" action="{{ route('zkteco.devices.mappings.update', [$device, $mapping]) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Device PIN</label><br>
            <input type="text" value="{{ $mapping->device_pin }}" disabled>
        </div>

        <div>
            <label>Employee Code *</label><br>
            <input type="text" name="employee_code" value="{{ old('employee_code', $mapping->employee_code) }}" required>
            @error('employee_code') <div style="color: red;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $mapping->is_active) ? 'checked' : '' }}>
                Active
            </label>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit">Update Mapping</button>
            <a href="{{ route('zkteco.devices.mappings.index', $device) }}">Cancel</a>
        </div>
    </form>
</body>
</html>
