<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Mappings</title>
</head>
<body>
    <h1>Employee Mappings - {{ $device->serial_number }}</h1>

    <div style="margin-bottom: 20px;">
        <a href="{{ route('zkteco.devices.index') }}">Back to Devices</a>
        <a href="{{ route('zkteco.devices.mappings.create', $device) }}">Add Mapping</a>
    </div>

    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>PIN</th>
                <th>Employee Code</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mappings as $mapping)
                <tr>
                    <td>{{ $mapping->device_pin }}</td>
                    <td>{{ $mapping->employee_code }}</td>
                    <td>
                        @if ($mapping->is_active)
                            <span style="color: green;">Active</span>
                        @else
                            <span style="color: red;">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('zkteco.devices.mappings.edit', [$device, $mapping]) }}">Edit</a>
                        <form method="POST" action="{{ route('zkteco.devices.mappings.destroy', [$device, $mapping]) }}" style="display: inline;" onsubmit="return confirm('Delete?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No mappings found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $mappings->links() }}
</body>
</html>
