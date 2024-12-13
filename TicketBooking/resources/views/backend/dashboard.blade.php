@extends('backend.master')

@section('title', 'Tickets')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}</p>

    <ul>
        <li><a href="{{ route('admin.vehicle-types.index') }}">Manage Vehicle Types</a></li>
        <li><a href="{{ route('admin.vehicles.index') }}">Manage Vehicles</a></li>
        <li><a href="{{ route('admin.trip-schedules.index') }}">Manage Trip Schedules</a></li>
        <li><a href="{{ route('admin.trips.index') }}">Manage Trips</a></li>
        <li><a href="{{ route('admin.tickets.index') }}">Manage Tickets</a></li>
    </ul>
</div>

@endsection
