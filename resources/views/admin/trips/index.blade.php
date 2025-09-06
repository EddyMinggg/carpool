@extends('admin.layout')

@section('title', 'Manage Trips')

@section('content')
    <h1 class="mb-4">Manage Trips</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Trips List</h5>
            <a href="{{ route('admin.trips.create') }}" class="btn btn-primary">Create New Trip</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Trip ID</th>
                            <th>Creator</th>
                            <th>Start Place</th>
                            <th>End Place</th>
                            <th>Departure Time</th>
                            <th>Max People</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trips as $trip)
                            <tr>
                                <td>{{ $trip->trip_id }}</td>
                                <td>{{ $trip->creator->name ?? 'N/A' }}</td>
                                <td>{{ $trip->start_place }}</td>
                                <td>{{ $trip->end_place }}</td>
                                <td>{{ $trip->plan_departure_time->format('H:i') }}</td>
                                <td>{{ $trip->max_people }}</td>
                                <td>
                                    <span class="badge bg-{{ $trip->trip_status === 'pending' ? 'info' : ($trip->trip_status === 'voting' ? 'warning' : ($trip->trip_status === 'completed' ? 'success' : 'danger')) }}">
                                        {{ ucfirst($trip->trip_status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.trips.show', ['trip' => $trip->trip_id]) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.trips.edit', ['trip' => $trip->trip_id]) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.trips.destroy', ['trip' => $trip->trip_id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this trip?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No trips found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $trips->links() }}
        </div>
    </div>
@endsection