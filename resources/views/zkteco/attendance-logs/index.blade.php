@extends('layouts.admin')

@section('title', 'Attendance Logs')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Attendance Logs</h3>
                    <div class="card-tools">
                        <a href="{{ route('zkteco.attendance-logs.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add Log
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" action="{{ route('zkteco.attendance-logs.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by Serial or PIN..." value="{{ request('search') }}">
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
                                    <th>Employee PIN</th>
                                    <th>Attendance Time</th>
                                    <th>Status</th>
                                    <th>Verify Type</th>
                                    <th>Work Code</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendanceLogs as $log)
                                    <tr>
                                        <td>{{ $log->serial_number }}</td>
                                        <td>{{ $log->employee_pin }}</td>
                                        <td>{{ $log->attendance_time->format('Y-m-d H:i') }}</td>
                                        <td>{{ $log->status }}</td>
                                        <td>{{ $log->verify_type }}</td>
                                        <td>{{ $log->work_code }}</td>
                                        <td>
                                            <a href="{{ route('zkteco.attendance-logs.edit', $log) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('zkteco.attendance-logs.destroy', $log) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this log?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No attendance logs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

                    @if ($attendanceLogs->hasPages())
                        <div class="mt-3">
                            {{ $attendanceLogs->links() }}
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
