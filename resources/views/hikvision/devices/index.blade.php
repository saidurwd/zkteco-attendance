@extends('layouts.admin')

@section('title', 'Hikvision Devices')

@section('content_header')
    <x-adminlte-content-header
        title="Hikvision Devices"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Hikvision Devices']]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hikvision Devices</h3>
                    <div class="card-tools">
                        <a href="{{ route('hikvision.devices.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add Device
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" action="{{ route('hikvision.devices.index') }}" class="mb-3">
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
                                    <th>Device Name</th>
                                    <th>Serial</th>
                                    <th>Model</th>
                                    <th>IP Address</th>
                                    <th>Location</th>
                                    <th>Username</th>
                                    <th>Status</th>
                                    <th>Last Event</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($devices as $device)
                                    <tr>
                                        <td>{{ $device->device_name }}</td>
                                        <td>{{ $device->device_serial }}</td>
                                        <td>{{ $device->device_model }}</td>
                                        <td>{{ $device->ip_address }}</td>
                                        <td>{{ $device->location }}</td>
                                        <td>{{ $device->username }}</td>
                                        <td>
                                            @if ($device->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $device->last_event_at?->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('hikvision.devices.edit', $device) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('hikvision.devices.destroy', $device) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this device?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No Hikvision devices found.</td>
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
