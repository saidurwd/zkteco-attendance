@extends('layouts.admin')

@section('title', 'Hikvision Attendance Report')

@section('content_header')
    <x-adminlte-content-header
        title="Hikvision Attendance Report"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Hikvision Attendance Report']]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hikvision Attendance Report</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" action="{{ route('hikvision.reports.attendance') }}" class="mb-4">
                        <div class="row">
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
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="employee_no" class="form-label">Employee No</label>
                                    <input type="text" name="employee_no" id="employee_no" class="form-control" value="{{ request('employee_no') }}" placeholder="EMP001">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="device_id" class="form-label">Device</label>
                                    <select name="device_id" id="device_id" class="form-control">
                                        <option value="">All Devices</option>
                                        @foreach($devices as $device)
                                            <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                                                {{ $device->device_name }} ({{ $device->device_serial }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <a href="{{ route('hikvision.reports.attendance') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Employee No</th>
                                    <th>Device</th>
                                    <th>Serial</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Total Entries</th>
                                    <th>Total Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $row)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($row->attendance_date)->timezone($tz)->format('Y-m-d') }}</td>
                                        <td>{{ $row->employee_no }}</td>
                                        <td>{{ $row->device_name }}</td>
                                        <td>{{ $row->device_serial }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->clock_in)->timezone($tz)->format('H:i:s') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->clock_out)->timezone($tz)->format('H:i:s') }}</td>
                                        <td>{{ $row->total_entries }}</td>
                                        <td>{{ $row->total_hours }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No attendance records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

                    @if ($reports->hasPages())
                        <div class="mt-3">
                            {{ $reports->links() }}
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
