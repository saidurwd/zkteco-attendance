@extends('layouts.admin')

@section('title', 'Devices')

@section('content_header')
    <x-adminlte-content-header 
        title="Devices"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Devices']]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ZKTeco Devices</h3>
                    <div class="card-tools">
                        <a href="{{ route('zkteco.devices.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add Device
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" action="{{ route('zkteco.devices.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search devices..." value="{{ request('search') }}">
                            <button class="btn btn-default" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
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
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $device->last_seen_at?->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ route('zkteco.devices.edit', $device) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('zkteco.devices.toggle', $device) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-info btn-sm" title="Toggle Status">
                                                    <i class="bi bi-toggle-on"></i> Toggle
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('zkteco.devices.destroy', $device) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this device?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                            <a href="{{ route('zkteco.devices.mappings.index', $device) }}" class="btn btn-secondary btn-sm">
                                                <i class="bi bi-link-45deg"></i> Mappings
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No devices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

                    @if ($devices->hasPages())
                        <div class="mt-3">
                            {{ $devices->links() }}
                        </div>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@stop
