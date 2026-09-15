@extends('layouts.admin')

@section('title', 'Employee Mappings')

@section('content_header')
    <x-adminlte-content-header 
        title="{{ 'Employee Mappings - ' . $device->serial_number }}"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Devices', 'url' => route('zkteco.devices.index')],
            ['label' => 'Employee Mappings']
        ]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Employee Mappings - {{ $device->serial_number }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('zkteco.devices.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to Devices
                        </a>
                        <a href="{{ route('zkteco.devices.mappings.create', $device) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add Mapping
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
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
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('zkteco.devices.mappings.edit', [$device, $mapping]) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('zkteco.devices.mappings.destroy', [$device, $mapping]) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this mapping?')">
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
                                        <td colspan="4" class="text-center">No mappings found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

                    @if ($mappings->hasPages())
                        <div class="mt-3">
                            {{ $mappings->links() }}
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
