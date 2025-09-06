<?php
/**
 * Admin Dashboard
 * Shows key statistics and overview
 */
?>
@extends('admin.layout')

@section('content')
    <h1 class="mb-4">Dashboard</h1>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white p-4">
                <h5>Total Users</h5>
                <h2>{{ $totalUsers }}</h2>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white p-4">
                <h5>Total Trips</h5>
                <h2>{{ $totalTrips }}</h2>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-warning text-dark p-4">
                <h5>Pending Trips</h5>
                <h2>{{ $pendingTrips }}</h2>
            </div>
        </div>
    </div>
@endsection
