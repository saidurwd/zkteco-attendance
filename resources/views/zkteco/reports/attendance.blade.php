@extends('layouts.admin')

@section('title', 'Attendance Report')

@section('content_header')
    <x-adminlte-content-header 
        title="Attendance Report"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Attendance Report']]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Attendance Report</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" action="{{ route('zkteco.reports.attendance') }}" class="mb-4">
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
                                    <label for="employee_id" class="form-label">Employee</label>
                                    <select name="employee_id" id="employee_id" class="form-control">
                                        <option value="">All Employees</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                                {{ $emp->employee_id }} - {{ $emp->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <select name="department" id="department" class="form-control">
                                        <option value="">All Departments</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                                {{ $dept }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <a href="{{ route('zkteco.reports.attendance') }}" class="btn btn-secondary">
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
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Site</th>
                                    <th>Device Serial</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Total Entries</th>
                                    <th>Total Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $row)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($row->attendance_date)->format('Y-m-d') }}</td>
                                        <td>{{ $row->employee_code }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->department }}</td>
                                        <td>{{ $row->position }}</td>
                                        <td>{{ $row->site_code }}</td>
                                        <td>{{ $row->serial_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->clock_in)->format('H:i:s') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->clock_out)->format('H:i:s') }}</td>
                                        <td>{{ $row->total_entries }}</td>
                                        <td>{{ $row->total_hours }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No attendance records found.</td>
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
