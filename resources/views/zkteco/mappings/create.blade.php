<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Mapping</title>
</head>
<body>
    <h1>Add Employee Mapping - {{ $device->serial_number }}</h1>

    <form method="POST" action="{{ route('zkteco.devices.mappings.store', $device) }}">
        @csrf

        <div>
            <label>Device PIN *</label><br>
            <input type="text" name="device_pin" value="{{ old('device_pin') }}" required>
            @error('device_pin') <div style="color: red;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Employee Code *</label><br>
            <input type="text" name="employee_code" value="{{ old('employee_code') }}" required>
            @error('employee_code') <div style="color: red;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-top: 20px;">
            <button type="submit">Create Mapping</button>
            <a href="{{ route('zkteco.devices.mappings.index', $device) }}">Cancel</a>
        </div>
    </form>
</body>
</html>
