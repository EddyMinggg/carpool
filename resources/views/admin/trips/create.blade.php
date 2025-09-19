@extends('admin.layout')

@section('title', 'Trip Management - Create')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Trip</h2>

    <!-- Create Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
        <form action="{{ route('admin.trips.store') }}" method="POST">
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @csrf

            <input type="hidden" name="creator_id" value="{{ auth()->user()->id }}">
            
            <input type="hidden" name="dropoff_location" value="华发">
          
            <input type="hidden" name="trip_status" value="awaiting">

            <!-- StartPoint -->
            <div class="mb-6 p-4 bg-blue-50 rounded-md border border-blue-100">
                <p class="text-sm text-blue-800">
                    <i class="fa fa-info-circle mr-2"></i>
                    The starting point will be automatically detected by the system when the user uses it, and there is no need to set it manually
                </p>
            </div>

            <!-- Destination -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">End Place</label>
                <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    华发
                </div>
            </div>

            <!-- Departure Time -->
            <div class="mb-4">
                <label for="planned_departure_time" class="block text-sm font-medium text-gray-700 mb-1">Planned Departure Time <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="planned_departure_time" id="planned_departure_time" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('planned_departure_time') }}">
                @error('planned_departure_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Maximum -->
            <div class="mb-4">
                <label for="max_people" class="block text-sm font-medium text-gray-700 mb-1">Max People <span class="text-red-500">*</span></label>
                <input type="number" name="max_people" id="max_people" min="1" max="10" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('max_people', 4) }}">
                @error('max_people')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Private trip -->
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_private" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        {{ old('is_private') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Private Trip (invitation only)</span>
                </label>
            </div>

            <!-- Base Price -->
            <div class="mb-6">
                <label for="base_price" class="block text-sm font-medium text-gray-700 mb-1">Base Price (¥) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="base_price" id="base_price" min="0" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('base_price', 700.00) }}">
                @error('base_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit button -->
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                    Create Trip
                </button>
                <a href="{{ route('admin.trips.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
