@extends('admin.layout')

@section('title', 'Trip Management - List')

@section('content')
    <div class="flex justify-between items-center mb-6 w-full">
        <h2 class="text-2xl font-bold text-gray-800">Trip List</h2>
        <a href="{{ route('admin.trips.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
            Create New Trip
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden w-full max-w-none">
        <div class="p-4 sm:p-6 w-full">
            <div class="overflow-x-auto w-full" style="min-width: 800px;">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trip ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creator</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Place</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Place</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departure Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max People</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($trips as $trip)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->trip_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->creator->name ?? 'Unknown User' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->start_place }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->end_place }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->plan_departure_time->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->max_people }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $trip->trip_status === 'pending' ? 'bg-blue-100 text-blue-800' : 
                                           ($trip->trip_status === 'voting' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($trip->trip_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst($trip->trip_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.trips.show', $trip->trip_id) }}" class="text-blue-600 hover:text-blue-900 mr-3 transition-colors">View</a>
                                    <a href="{{ route('admin.trips.edit', $trip->trip_id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3 transition-colors">Edit</a>
                                    <form action="{{ route('admin.trips.destroy', $trip->trip_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" onclick="return confirm('Are you sure you want to delete this?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500">
                                    No trip data available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($trips->hasPages())
                <div class="mt-6 w-full">
                    {{ $trips->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
