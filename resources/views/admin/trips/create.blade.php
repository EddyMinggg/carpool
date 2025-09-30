@extends('admin.layout')

@section('title', 'Trip Management - Create')
@section('page-title', 'Create Trip')

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

        .mobile-readonly {
            background-color: #f9fafb;
            color: #6b7280;
            cursor: not-allowed;
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

        .mobile-error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .mobile-error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-error-item {
            color: #dc2626;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .mobile-required {
            color: #dc2626;
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
                <form action="{{ route('admin.trips.store') }}" method="POST">
                    @if ($errors->any())
                        <div class="mobile-error-box">
                            <ul class="mobile-error-list">
                                @foreach ($errors->all() as $error)
                                    <li class="mobile-error-item">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf

                    <input type="hidden" name="creator_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="dropoff_location" value="华发">
                    <input type="hidden" name="trip_status" value="awaiting">

                    <!-- Info Box for Start Point -->
                    <div class="mobile-info-box">
                        <p class="mobile-info-text">
                            <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                            The starting point will be automatically detected when users join the trip
                        </p>
                    </div>

                    <!-- Destination (Read-only) -->
                    <div class="mobile-form-group">
                        <label class="mobile-form-label">End Place</label>
                        <div class="mobile-form-input mobile-readonly">
                            华发
                        </div>
                    </div>

                    <!-- Departure Time -->
                    <div class="mobile-form-group">
                        <label for="planned_departure_time" class="mobile-form-label">
                            Planned Departure Time <span class="mobile-required">*</span>
                        </label>
                        <input type="datetime-local" name="planned_departure_time" id="planned_departure_time" 
                            class="mobile-form-input"
                            value="{{ old('planned_departure_time') }}">
                        @error('planned_departure_time')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max People -->
                    <div class="mobile-form-group">
                        <label for="max_people" class="mobile-form-label">
                            Max People <span class="mobile-required">*</span>
                        </label>
                        <input type="number" name="max_people" id="max_people" min="1" max="10" 
                            class="mobile-form-input"
                            value="{{ old('max_people', 4) }}">
                        @error('max_people')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Base Price -->
                    <div class="mobile-form-group">
                        <label for="base_price" class="mobile-form-label">
                            Base Price (¥) <span class="mobile-required">*</span>
                        </label>
                        <input type="number" step="0.01" name="base_price" id="base_price" min="0" 
                            class="mobile-form-input"
                            value="{{ old('base_price', 700.00) }}">
                        @error('base_price')
                            <p class="mobile-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mobile-form-actions">
                        <button type="submit" class="mobile-action-btn mobile-btn-blue">
                            Create Trip
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

                    <div class="mb-6">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                        <select name="type" id="type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="normal" {{ old('type') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        </select>
                        @error('type')
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
        </div>
    @endif
@endsection
