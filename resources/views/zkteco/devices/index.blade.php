<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZKTeco Devices</title>
</head>
<body>
    <h1>ZKTeco Devices</h1>

    <div style="margin-bottom: 20px;">
        <a href="{{ route('zkteco.devices.create') }}">Add Device</a>
    </div>

    <form method="GET" action="{{ route('zkteco.devices.index') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <table border="1" cellpadding="8" cellspacing="0" style="margin-top: 20px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Serial</th>
                <th>Name</th>
                <th>Model</th>
                <th>Site</th>
                <th>Location</th>
                <th>IP</th>
                <th>Status</th>
                <th>Last Seen</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($devices as $device)
                <tr>
                    <td>{{ $device->serial_number }}</td>
                    <td>{{ $device->device_name }}</td>
                    <td>{{ $device->model }}</td>
                    <td>{{ $device->site_code }}</td>
                    <td>{{ $device->location }}</td>
                    <td>{{ $device->device_ip }}</td>
                    <td>
                        @if ($device->is_active)
                            <span style="color: green;">Active</span>
                        @else
                            <span style="color: red;">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $device->last_seen_at?->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('zkteco.devices.edit', $device) }}">Edit</a>
                        <form method="POST" action="{{ route('zkteco.devices.toggle', $device) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Toggle</button>
                        </form>
                        <form method="POST" action="{{ route('zkteco.devices.destroy', $device) }}" style="display: inline;" onsubmit="return confirm('Delete?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                        <a href="{{ route('zkteco.devices.mappings.index', $device) }}">Mappings</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No devices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $devices->links() }}
</body>
</html>
