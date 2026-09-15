@extends('adminlte::page')

@section('title', 'Attendance Nexus')

@section('content_header')
    <x-adminlte-content-header 
        title="Dashboard"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Dashboard']
        ]"
    />
@stop

@section('content')
    <p>Welcome to your admin panel. Use the sidebar to navigate.</p>
@stop
