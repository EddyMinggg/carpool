@extends('admin.layout')

@section('title', 'Trip Management - Edit')
@section('page-title', 'Edit Trip')

@section('content')
    <style>
        /* Mobile reset styles */
        @media (max-width: 768px) {
            .container {
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
            
            body {
                overflow-x: hidden !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
            }
            
            * {
                box-sizing: border-box !important;
            }
        }

        /* Mobile form styles */
        .mobile-form-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .mobile-form-group {
            margin-bottom: 20px;
        }

        .mobile-form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .mobile-form-input, .mobile-form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            background: white;
            transition: border-color 0.2s;
        }

        .mobile-form-input:focus, .mobile-form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .mobile-info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .mobile-info-text {
            color: #1e40af;
            font-size: 14px;
            margin: 0;
        }

        .mobile-form-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .mobile-action-btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .mobile-btn-blue {
            background-color: #3b82f6;
            color: white;
        }

        .mobile-btn-blue:hover {
            background-color: #2563eb;
            color: white;
        }

        .mobile-btn-gray {
            background-color: #6b7280;
            color: white;
        }

        .mobile-btn-gray:hover {
            background-color: #4b5563;
            color: white;
        }

        .mobile-error {
            color: #dc2626;
            font-size: 14px;
            margin-top: 4px;
        }

        .mobile-readonly {
            background-color: #f9fafb;
            color: #6b7280;
        }

        /* Desktop styles - hide on mobile */
        @media (max-width: 768px) {
            .desktop-only {
                display: none !important;
            }
        }

        /* Mobile styles - hide on desktop */
        @media (min-width: 769px) {
            .mobile-only {
                display: none !important;
            }
        }
    </style>

    @if($isMobile)
        <!-- Mobile Layout -->
        <div class="mobile-only" style="padding: 12px; background-color: #f1f5f9; min-height: 100vh;">
            <div class="mobile-form-container">
                <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Creator Selection -->
                    <div class="mobile-form-group">
                        <label for="creator_id" class="mobile-form-label">Creator</label>
                        <select name="creator_id" id="creator_id" class="mobile-form-select">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $trip->creator_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->username }}
                                </option>
                            @endforeach
                        </select>
                        @error('creator_id')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="mobile-info-box">
                        <p class="mobile-info-text">
                            <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                            The starting point is set by individual users when they join the trip, not by the admin
                        </p>
                    </div>

                    <!-- End Place -->
                    <div class="mobile-form-group">
                        <label for="dropoff_location" class="mobile-form-label">End Place</label>
                        <input type="text" name="dropoff_location" id="dropoff_location" 
                            class="mobile-form-input"
                            value="{{ old('dropoff_location', $trip->dropoff_location) }}">
                        @error('dropoff_location')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Departure Time -->
                    <div class="mobile-form-group">
                        <label for="planned_departure_time" class="mobile-form-label">Planned Departure Time</label>
                        <input type="datetime-local" name="planned_departure_time" id="planned_departure_time" 
                            class="mobile-form-input"
                            value="{{ old('planned_departure_time', $trip->planned_departure_time->format('Y-m-d\TH:i')) }}">
                        @error('planned_departure_time')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max People -->
                    <div class="mobile-form-group">
                        <label for="max_people" class="mobile-form-label">Max People</label>
                        <input type="number" name="max_people" id="max_people" min="1" max="10" 
                            class="mobile-form-input"
                            value="{{ old('max_people', $trip->max_people) }}">
                        @error('max_people')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Min Passengers -->
                    <div class="mobile-form-group">
                        <label for="min_passengers" class="mobile-form-label">Minimum Passengers</label>
                        <input type="number" name="min_passengers" id="min_passengers" min="1" max="10" 
                            class="mobile-form-input"
                            value="{{ old('min_passengers', $trip->min_passengers) }}">
                        @error('min_passengers')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price Per Person -->
                    <div class="mobile-form-group">
                        <label for="price_per_person" class="mobile-form-label">Price Per Person (HK$)</label>
                        <input type="number" step="0.01" name="price_per_person" id="price_per_person" min="0" 
                            class="mobile-form-input"
                            value="{{ old('price_per_person', $trip->price_per_person) }}">
                        @error('price_per_person')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Trip Type -->
                    <div class="mobile-form-group">
                        <label for="type" class="mobile-form-label">Trip Type</label>
                        <select name="type" id="type" class="mobile-form-select">
                            <option value="normal" {{ $trip->type === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="golden" {{ $trip->type === 'golden' ? 'selected' : '' }}>Golden Hour</option>
                        </select>
                        @error('type')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Four Person Discount -->
                    <div class="mobile-form-group" id="discountField" style="{{ $trip->type === 'golden' ? 'display: none;' : '' }}">
                        <label for="four_person_discount" class="mobile-form-label">4-Person Group Discount (HK$)</label>
                        <input type="number" step="0.01" name="four_person_discount" id="four_person_discount" min="0" 
                            class="mobile-form-input"
                            value="{{ old('four_person_discount', $trip->four_person_discount) }}">
                        @error('four_person_discount')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Trip Status -->
                    <div class="mobile-form-group">
                        <label for="trip_status" class="mobile-form-label">Trip Status</label>
                        <select name="trip_status" id="trip_status" class="mobile-form-select">
                            <option value="awaiting" {{ $trip->trip_status === 'awaiting' ? 'selected' : '' }}>Awaiting</option>
                            <option value="departed" {{ $trip->trip_status === 'departed' ? 'selected' : '' }}>Departed</option>
                            <option value="charging" {{ $trip->trip_status === 'charging' ? 'selected' : '' }}>Charging</option>
                            <option value="completed" {{ $trip->trip_status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $trip->trip_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('trip_status')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mobile-form-actions">
                        <button type="submit" class="mobile-action-btn mobile-btn-blue">
                            Update Trip
                        </button>
                        <a href="{{ route('admin.trips.index') }}" class="mobile-action-btn mobile-btn-gray">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    @else
        <!-- Desktop Layout -->
        <div class="desktop-only">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Trip #{{ $trip->id }}</h2>

            <!-- Edit Form Card -->
            <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
                <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Creator Selection -->
                    <div class="mb-4">
                        <label for="creator_id" class="block text-sm font-medium text-gray-700 mb-1">Creator</label>
                        <select name="creator_id" id="creator_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $trip->creator_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->username }}
                                </option>
                            @endforeach
                        </select>
                        @error('creator_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- StartPoint Note -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-md border border-blue-100">
                        <p class="text-sm text-blue-800">
                            <i class="fa fa-info-circle mr-2"></i>
                            The starting point is set by individual users when they join the trip, not by the admin
                        </p>
                    </div>

                    <!-- End Place -->
                    <div class="mb-4">
                        <label for="dropoff_location" class="block text-sm font-medium text-gray-700 mb-1">End Place</label>
                        <input type="text" name="dropoff_location" id="dropoff_location" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('dropoff_location', $trip->dropoff_location) }}">
                        @error('dropoff_location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Departure Time -->
                    <div class="mb-4">
                        <label for="planned_departure_time" class="block text-sm font-medium text-gray-700 mb-1">Planned Departure Time</label>
                        <input type="datetime-local" name="planned_departure_time" id="planned_departure_time" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('planned_departure_time', $trip->planned_departure_time->format('Y-m-d\TH:i')) }}">
                        @error('planned_departure_time')
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

                    <!-- Min Passengers -->
                    <div class="mb-4">
                        <label for="min_passengers" class="block text-sm font-medium text-gray-700 mb-1">Minimum Passengers</label>
                        <input type="number" name="min_passengers" id="min_passengers" min="1" max="10" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('min_passengers', $trip->min_passengers) }}">
                        @error('min_passengers')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price Per Person -->
                    <div class="mb-4">
                        <label for="price_per_person" class="block text-sm font-medium text-gray-700 mb-1">Price Per Person (HK$)</label>
                        <input type="number" step="0.01" name="price_per_person" id="price_per_person" min="0" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('price_per_person', $trip->price_per_person) }}">
                        @error('price_per_person')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Trip Type -->
                    <div class="mb-4">
                        <label for="desktop_type" class="block text-sm font-medium text-gray-700 mb-1">Trip Type</label>
                        <select name="type" id="desktop_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="normal" {{ $trip->type === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="golden" {{ $trip->type === 'golden' ? 'selected' : '' }}>Golden Hour</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Four Person Discount -->
                    <div class="mb-4" id="desktopDiscountField" style="{{ $trip->type === 'golden' ? 'display: none;' : '' }}">
                        <label for="four_person_discount" class="block text-sm font-medium text-gray-700 mb-1">4-Person Group Discount (HK$)</label>
                        <input type="number" step="0.01" name="four_person_discount" id="four_person_discount" min="0" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('four_person_discount', $trip->four_person_discount) }}">
                        @error('four_person_discount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Trip Status -->
                    <div class="mb-6">
                        <label for="trip_status" class="block text-sm font-medium text-gray-700 mb-1">Trip Status</label>
                        <select name="trip_status" id="trip_status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="awaiting" {{ $trip->trip_status === 'awaiting' ? 'selected' : '' }}>Awaiting</option>
                            <option value="departed" {{ $trip->trip_status === 'departed' ? 'selected' : '' }}>Departed</option>
                            <option value="charging" {{ $trip->trip_status === 'charging' ? 'selected' : '' }}>Charging</option>
                            <option value="completed" {{ $trip->trip_status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $trip->trip_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('trip_status')
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
        </div>
    @endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const timeSlotSelect = document.getElementById('type');
    const desktopTimeSlotSelect = document.getElementById('desktop_type');
    const priceInput = document.getElementById('price_per_person');
    const mobileDiscountField = document.getElementById('discountField');
    const desktopDiscountField = document.getElementById('desktopDiscountField');
    
    function updateBusinessRules() {
        const currentSelect = timeSlotSelect || desktopTimeSlotSelect;
        if (!currentSelect) return;
        
        const isGoldenHour = currentSelect.value === 'golden';
        
        if (isGoldenHour) {
            if (mobileDiscountField) mobileDiscountField.style.display = 'none';
            if (desktopDiscountField) desktopDiscountField.style.display = 'none';
            if (priceInput) priceInput.value = '250.00';
        } else {
            if (mobileDiscountField) mobileDiscountField.style.display = 'block';
            if (desktopDiscountField) desktopDiscountField.style.display = 'block';
            if (priceInput && currentSelect.value === 'normal') priceInput.value = '275.00';
        }
    }
    
    // Event listeners
    if (timeSlotSelect) {
        timeSlotSelect.addEventListener('change', updateBusinessRules);
    }
    
    if (desktopTimeSlotSelect) {
        desktopTimeSlotSelect.addEventListener('change', updateBusinessRules);
    }
    
    // Initial update
    updateBusinessRules();
});
</script>
@endpush