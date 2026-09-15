@extends('layouts.admin')

@section('title', 'Hikvision Events')

@section('content_header')
    <x-adminlte-content-header
        title="Hikvision Events"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Hikvision Events']]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hikvision Events</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" action="{{ route('hikvision.events.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="device_id" class="form-label">Device</label>
                                    <select name="device_id" id="device_id" class="form-control">
                                        <option value="">All Devices</option>
                                        @foreach($devices as $device)
                                            <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                                                {{ $device->device_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="employee_no" class="form-label">Employee No</label>
                                    <input type="text" name="employee_no" id="employee_no" class="form-control" value="{{ request('employee_no') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="processing_status" class="form-label">Status</label>
                                    <select name="processing_status" id="processing_status" class="form-control">
                                        <option value="">All</option>
                                        <option value="PENDING" {{ request('processing_status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                        <option value="PROCESSED" {{ request('processing_status') == 'PROCESSED' ? 'selected' : '' }}>PROCESSED</option>
                                        <option value="FAILED" {{ request('processing_status') == 'FAILED' ? 'selected' : '' }}>FAILED</option>
                                        <option value="IGNORED" {{ request('processing_status') == 'IGNORED' ? 'selected' : '' }}>IGNORED</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <a href="{{ route('hikvision.events.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Device</th>
                                    <th>Event Time</th>
                                    <th>Employee No</th>
                                    <th>Employee Name</th>
                                    <th>Attendance Status</th>
                                    <th>Verify Mode</th>
                                    <th>Serial No</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($query as $event)
                                    <tr>
                                        <td>{{ $event->id }}</td>
                                        <td>{{ $event->device?->device_name ?? 'N/A' }}</td>
                                        <td>{{ $event->event_time?->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $event->employee_no }}</td>
                                        <td>{{ $event->employee_name }}</td>
                                        <td>{{ $event->attendance_status }}</td>
                                        <td>{{ $event->verify_mode }}</td>
                                        <td>{{ $event->serial_no }}</td>
                                        <td>
                                            @switch($event->processing_status)
                                                @case('PROCESSED')
                                                    <span class="badge bg-success">Processed</span>
                                                    @break
                                                @case('PENDING')
                                                    <span class="badge bg-warning">Pending</span>
                                                    @break
                                                @case('FAILED')
                                                    <span class="badge bg-danger">Failed</span>
                                                    @break
                                                @case('IGNORED')
                                                    <span class="badge bg-secondary">Ignored</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="{{ route('hikvision.events.show', $event) }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No Hikvision events found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

                    @if ($query->hasPages())
                        <div class="mt-3">
                            {{ $query->links() }}
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
