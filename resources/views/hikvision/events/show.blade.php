@extends('layouts.admin')

@section('title', 'Hikvision Event Details')

@section('content_header')
    @php
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Hikvision Events', 'url' => route('hikvision.events.index')],
            ['label' => 'Event #' . $event->id],
        ];
    @endphp
    <x-adminlte-content-header
        title="Hikvision Event Details"
        :breadcrumbs="$breadcrumbs"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Event #{{ $event->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('hikvision.events.index') }}" class="btn btn-default btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">ID</th>
                            <td>{{ $event->id }}</td>
                        </tr>
                        <tr>
                            <th>Device</th>
                            <td>{{ $event->device?->device_name ?? 'N/A' }} ({{ $event->device?->device_serial ?? 'N/A' }})</td>
                        </tr>
                        <tr>
                            <th>Event Type</th>
                            <td>{{ $event->event_type }}</td>
                        </tr>
                        <tr>
                            <th>Event State</th>
                            <td>{{ $event->event_state }}</td>
                        </tr>
                        <tr>
                            <th>Event Time</th>
                            <td>{{ $event->event_time?->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Employee No</th>
                            <td>{{ $event->employee_no }}</td>
                        </tr>
                        <tr>
                            <th>Employee Name</th>
                            <td>{{ $event->employee_name }}</td>
                        </tr>
                        <tr>
                            <th>Card No</th>
                            <td>{{ $event->card_no }}</td>
                        </tr>
                        <tr>
                            <th>Major Event Type</th>
                            <td>{{ $event->major_event_type }}</td>
                        </tr>
                        <tr>
                            <th>Sub Event Type</th>
                            <td>{{ $event->sub_event_type }}</td>
                        </tr>
                        <tr>
                            <th>Attendance Status</th>
                            <td>{{ $event->attendance_status }}</td>
                        </tr>
                        <tr>
                            <th>Verify Mode</th>
                            <td>{{ $event->verify_mode }}</td>
                        </tr>
                        <tr>
                            <th>Serial No</th>
                            <td>{{ $event->serial_no }}</td>
                        </tr>
                        <tr>
                            <th>Payload Format</th>
                            <td>{{ $event->payload_format }}</td>
                        </tr>
                        <tr>
                            <th>Processing Status</th>
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
                        </tr>
                        <tr>
                            <th>Processing Message</th>
                            <td>{{ $event->processing_message ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Received At</th>
                            <td>{{ $event->received_at?->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Processed At</th>
                            <td>{{ $event->processed_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</td>
                        </tr>
                    </table>

                    <h5 class="mt-4">Raw Payload</h5>
                    <pre class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">{{ $event->raw_payload }}</pre>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@stop
