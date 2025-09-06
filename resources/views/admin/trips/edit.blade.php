@extends('admin.layout')

@section('title', 'Trip Management - Edit')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Trip #{{ $trip->trip_id }}</h2>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
        <form action="{{ route('admin.trips.update', $trip->trip_id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Creator Selection -->
            <div class="mb-4">
                <label for="creator_id" class="block text-sm font-medium text-gray-700 mb-1">Creator</label>
                <select name="creator_id" id="creator_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $trip->creator_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('creator_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Start Place -->
            <div class="mb-4">
                <label for="start_place" class="block text-sm font-medium text-gray-700 mb-1">Start Place</label>
                <input type="text" name="start_place" id="start_place" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('start_place', $trip->start_place) }}">
                @error('start_place')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- End Place -->
            <div class="mb-4">
                <label for="end_place" class="block text-sm font-medium text-gray-700 mb-1">End Place</label>
                <input type="text" name="end_place" id="end_place" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('end_place', $trip->end_place) }}">
                @error('end_place')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Departure Time -->
            <div class="mb-4">
                <label for="plan_departure_time" class="block text-sm font-medium text-gray-700 mb-1">Planned Departure Time</label>
                <input type="datetime-local" name="plan_departure_time" id="plan_departure_time" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('plan_departure_time', $trip->plan_departure_time->format('Y-m-d\TH:i')) }}">
                @error('plan_departure_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Max People -->
            <div class="mb-4">
                <label for="max_people" class="block text-sm font-medium text-gray-700 mb-1">Max People</label>
                <input type="number" name="max_people" id="max_people" min="1" max="10" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('max_people', $trip->max_people) }}">
                @error('max_people')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Is Private -->
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_private" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        {{ $trip->is_private ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Private Trip (invitation only)</span>
                </label>
            </div>

            <!-- Trip Status -->
            <div class="mb-4">
                <label for="trip_status" class="block text-sm font-medium text-gray-700 mb-1">Trip Status</label>
                <select name="trip_status" id="trip_status" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pending" {{ $trip->trip_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="voting" {{ $trip->trip_status === 'voting' ? 'selected' : '' }}>Voting</option>
                    <option value="completed" {{ $trip->trip_status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $trip->trip_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('trip_status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Base Price -->
            <div class="mb-6">
                <label for="base_price" class="block text-sm font-medium text-gray-700 mb-1">Base Price (¥)</label>
                <input type="number" step="0.01" name="base_price" id="base_price" min="0" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('base_price', $trip->base_price) }}">
                @error('base_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update Trip
                </button>
                <a href="{{ route('admin.trips.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection