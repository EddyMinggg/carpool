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

                    <!-- Single Trip Form (Default) -->
                    <div id="singleTripForm">
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

                        <!-- Time Slot Type -->
                        <div class="mobile-form-group">
                            <label for="type" class="mobile-form-label">
                                Time Slot Type <span class="mobile-required">*</span>
                            </label>
                            <select name="type" id="type" class="mobile-form-select" required>
                                <option value="golden" {{ old('type') == 'golden' ? 'selected' : '' }}>
                                    🌟 Golden Hour (Min 1 person to depart)
                                </option>
                                <option value="normal" {{ old('type') == 'normal' ? 'selected' : '' }}>
                                    ⏰ Regular Hour (Min 2 persons, 4-person discount available)
                                </option>
                            </select>
                            @error('type')
                                <p class="mobile-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price Per Person -->
                        <div class="mobile-form-group">
                            <label for="price_per_person" class="mobile-form-label">
                                Price Per Person (HK$) <span class="mobile-required">*</span>
                            </label>
                            <input type="number" step="0.01" name="price_per_person" id="price_per_person" min="0" 
                                class="mobile-form-input"
                                value="{{ old('price_per_person', 250.00) }}">
                            @error('price_per_person')
                                <p class="mobile-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Four Person Discount (only for Regular Hour) -->
                        <div class="mobile-form-group" id="discountField" style="display: none;">
                            <label for="four_person_discount" class="mobile-form-label">
                                4-Person Group Discount (HK$)
                            </label>
                            <input type="number" step="0.01" name="four_person_discount" id="four_person_discount" min="0" 
                                class="mobile-form-input"
                                value="{{ old('four_person_discount', 50.00) }}">
                            @error('four_person_discount')
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

                        <!-- Business Rules Information -->
                        <div class="mobile-info-box">
                            <p class="mobile-info-text" id="pricingInfo">
                                <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                                <span id="pricingDetails">Select time slot to see business rules</span>
                            </p>
                        </div>
                    </div>

                    <!-- Batch Trip Form (Hidden by default) -->
                    <div id="batchTripForm" style="display: none;">
                        <div class="mobile-info-box" style="margin-bottom: 16px;">
                            <p class="mobile-info-text">
                                <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                                Create multiple trips at once. Each trip will go to the same destination (华发).
                            </p>
                        </div>
                        
                        <div id="batchTripsContainer">
                            <!-- Batch trip items will be added here -->
                        </div>
                        
                        <button type="button" id="addBatchTripBtn" class="bg-green-500 text-white py-3 px-4 rounded-lg cursor-pointer w-full my-4 font-semibold">+ Add Another Trip</button>
                    </div>

                    <!-- Creation Mode Toggle -->
                    <div class="mobile-form-group">
                        <label class="mobile-form-label">Creation Mode:</label>
                        <div style="display: flex; gap: 8px;">
                            <label style="flex: 1; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; text-align: center; transition: all 0.2s;" class="creation-mode-btn" data-mode="single">
                                <input type="radio" name="creation_mode" value="single" checked style="display: none;">
                                <span style="font-weight: 600;">Single Trip</span>
                            </label>
                            <label style="flex: 1; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; text-align: center; transition: all 0.2s;" class="creation-mode-btn" data-mode="batch">
                                <input type="radio" name="creation_mode" value="batch" style="display: none;">
                                <span style="font-weight: 600;">Batch Create</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mobile-form-actions">
                        <button type="submit" class="mobile-action-btn mobile-btn-blue">
                            <span id="submitText">Create Trip</span>
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
        <div class="desktop-only mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Trip</h2>

            <!-- Create Form Card -->
            <div class="bg-white rounded-lg shadow-md w-full p-6">
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

                    <!-- Single Trip Form (Default) -->
                    <div id="desktopSingleTripForm">
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

                        <!-- Time Slot Type -->
                        <div class="mb-4">
                            <label for="desktop_type" class="block text-sm font-medium text-gray-700 mb-1">Time Slot Type <span class="text-red-500">*</span></label>
                            <select name="type" id="desktop_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="golden" {{ old('type') == 'golden' ? 'selected' : '' }}>
                                    🌟 Golden Hour (Min 1 person to depart)
                                </option>
                                <option value="normal" {{ old('type') == 'normal' ? 'selected' : '' }}>
                                    ⏰ Regular Hour (Min 2 persons, 4-person discount available)
                                </option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price Per Person -->
                        <div class="mb-4">
                            <label for="price_per_person" class="block text-sm font-medium text-gray-700 mb-1">Price Per Person (HK$) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="price_per_person" id="price_per_person" min="0" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('price_per_person', 250.00) }}">
                            @error('price_per_person')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Four Person Discount (only for Regular Hour) -->
                        <div class="mb-4" id="desktopDiscountField" style="display: none;">
                            <label for="four_person_discount" class="block text-sm font-medium text-gray-700 mb-1">4-Person Group Discount (HK$)</label>
                            <input type="number" step="0.01" name="four_person_discount" id="four_person_discount" min="0" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('four_person_discount', 50.00) }}">
                            @error('four_person_discount')
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

                        <!-- Business Rules Information -->
                        <div class="mb-6 p-4 bg-blue-50 rounded-md border border-blue-100">
                            <p class="text-sm text-blue-800" id="desktopPricingInfo">
                                <i class="fa fa-info-circle mr-2"></i>
                                <span id="desktopPricingDetails">Select time slot to see business rules</span>
                            </p>
                        </div>
                    </div>

                    <!-- Batch Trip Form (Hidden by default) -->
                    <div id="desktopBatchTripForm" style="display: none;">
                        <div class="mb-6 p-4 bg-blue-50 rounded-md border border-blue-100">
                            <p class="text-sm text-blue-800">
                                <i class="fa fa-info-circle mr-2"></i>
                                Create multiple trips at once. Each trip will go to the same destination (华发) but can have different departure times, time slots, and pricing.
                            </p>
                        </div>
                        
                        <div id="desktopBatchTripsContainer">
                            <!-- Batch trip items will be added here -->
                        </div>
                    
                        <button type="button" id="desktopAddBatchTripBtn" class="bg-green-600 text-white py-3 px-4 rounded-lg cursor-pointer w-full my-4 font-semibold">+ Add Another Trip</button>
                    </div>

                    <!-- Creation Mode Toggle -->
                    <div class="mb-6">
                        <div class="flex space-x-4">
                            <label class="flex-1 p-3 border-2 border-gray-300 rounded-md cursor-pointer transition-all hover:border-blue-300 creation-mode-btn-desktop" data-mode="single">
                                <input type="radio" name="creation_mode" value="single" checked class="hidden">
                                <div class="text-center">
                                    <div class="font-semibold text-gray-800">Single Trip</div>
                                    <div class="text-sm text-gray-600">Create one trip at a time</div>
                                </div>
                            </label>
                            <label class="flex-1 p-3 border-2 border-gray-300 rounded-md cursor-pointer transition-all hover:border-blue-300 creation-mode-btn-desktop" data-mode="batch">
                                <input type="radio" name="creation_mode" value="batch" class="hidden">
                                <div class="text-center">
                                    <div class="font-semibold text-gray-800">Batch Create</div>
                                    <div class="text-sm text-gray-600">Create multiple trips</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Hidden type field (always fixed for new dual-tier system) -->
                    <input type="hidden" name="type" value="fixed">

                    <!-- Submit button -->
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                            <span id="desktopSubmitText">Create Trip</span>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const timeSlotSelect = document.getElementById('type');
    const desktopTimeSlotSelect = document.getElementById('desktop_type');
    const priceInput = document.getElementById('price_per_person');
    const mobileDetails = document.getElementById('pricingDetails');
    const desktopDetails = document.getElementById('desktopPricingDetails');
    const mobileDiscountField = document.getElementById('discountField');
    const desktopDiscountField = document.getElementById('desktopDiscountField');
    
    let batchTripCount = 0;
    
    function updateBusinessRules() {
        const currentSelect = timeSlotSelect || desktopTimeSlotSelect;
        if (!currentSelect) return;
        
        const isGoldenHour = currentSelect.value === 'golden';
        let details = '';
        
        if (isGoldenHour) {
            details = 'Golden Hour Rules: Minimum 1 person to depart. No group discounts available.';
            if (mobileDiscountField) mobileDiscountField.style.display = 'none';
            if (desktopDiscountField) desktopDiscountField.style.display = 'none';
            if (priceInput) priceInput.value = '250.00';
        } else {
            details = 'Regular Hour Rules: Minimum 2 persons to depart. 4-person group discount available.';
            if (mobileDiscountField) mobileDiscountField.style.display = 'block';
            if (desktopDiscountField) desktopDiscountField.style.display = 'block';
            if (priceInput) priceInput.value = '275.00';
        }
        
        if (mobileDetails) mobileDetails.textContent = details;
        if (desktopDetails) desktopDetails.textContent = details;
    }
    
    function updateCreationModeUI() {
        // Update button styles for mobile
        document.querySelectorAll('.creation-mode-btn').forEach(btn => {
            const isActive = btn.querySelector('input[type="radio"]').checked;
            if (isActive) {
                btn.style.borderColor = '#3b82f6';
                btn.style.backgroundColor = '#eff6ff';
                btn.style.color = '#1e40af';
            } else {
                btn.style.borderColor = '#e5e7eb';
                btn.style.backgroundColor = 'white';
                btn.style.color = '#1f2937';
            }
        });
        
        // Update button styles for desktop
        document.querySelectorAll('.creation-mode-btn-desktop').forEach(btn => {
            const isActive = btn.querySelector('input[type="radio"]').checked;
            if (isActive) {
                btn.style.borderColor = '#3b82f6';
                btn.style.backgroundColor = '#eff6ff';
            } else {
                btn.style.borderColor = '#d1d5db';
                btn.style.backgroundColor = 'white';
            }
        });
        
        // Show/hide forms
        const mode = document.querySelector('input[name="creation_mode"]:checked').value;
        
        // Mobile forms
        const singleTripForm = document.getElementById('singleTripForm');
        const batchTripForm = document.getElementById('batchTripForm');
        const submitText = document.getElementById('submitText');
        
        // Desktop forms
        const desktopSingleTripForm = document.getElementById('desktopSingleTripForm');
        const desktopBatchTripForm = document.getElementById('desktopBatchTripForm');
        const desktopSubmitText = document.getElementById('desktopSubmitText');
        
        if (mode === 'single') {
            if (singleTripForm) singleTripForm.style.display = 'block';
            if (batchTripForm) batchTripForm.style.display = 'none';
            if (desktopSingleTripForm) desktopSingleTripForm.style.display = 'block';
            if (desktopBatchTripForm) desktopBatchTripForm.style.display = 'none';
            if (submitText) submitText.textContent = 'Create Trip';
            if (desktopSubmitText) desktopSubmitText.textContent = 'Create Trip';
        } else {
            if (singleTripForm) singleTripForm.style.display = 'none';
            if (batchTripForm) batchTripForm.style.display = 'block';
            if (desktopSingleTripForm) desktopSingleTripForm.style.display = 'none';
            if (desktopBatchTripForm) desktopBatchTripForm.style.display = 'block';
            if (submitText) submitText.textContent = 'Create All Trips';
            if (desktopSubmitText) desktopSubmitText.textContent = 'Create All Trips';
            
            // Add first batch trip if container is empty
            const batchContainer = document.getElementById('batchTripsContainer');
            const desktopBatchContainer = document.getElementById('desktopBatchTripsContainer');
            
            if (batchContainer && batchContainer.children.length === 0) {
                createBatchTripCard(batchContainer, true);
            }
            if (desktopBatchContainer && desktopBatchContainer.children.length === 0) {
                createBatchTripCard(desktopBatchContainer, false);
            }
        }
    }
    
    function createBatchTripCard(container, isMobile = false) {
        batchTripCount++;
        
        const tripCard = document.createElement('div');
        tripCard.className = isMobile ? 'mobile-form-container' : 'mb-4 p-4 border border-gray-300 rounded-md bg-gray-50';
        tripCard.style.position = 'relative';
        
        if (isMobile) {
            tripCard.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h4 style="font-weight: 600; color: #1f2937;">Trip ${batchTripCount}</h4>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" style="
                        background: #ef4444; color: white; border: none; 
                        padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;
                    ">Remove</button>
                </div>
                
                <div class="mobile-form-group">
                    <label class="mobile-form-label">Departure Time <span class="mobile-required">*</span></label>
                    <input type="datetime-local" name="batch_trips[${batchTripCount}][departure_time]" 
                           class="mobile-form-input" required>
                </div>
                
                <div class="mobile-form-group">
                    <label class="mobile-form-label">Time Slot Type <span class="mobile-required">*</span></label>
                    <select name="batch_trips[${batchTripCount}][type]" 
                            class="mobile-form-select batch-time-slot" data-index="${batchTripCount}" required>
                        <option value="golden">🌟 Golden Hour</option>
                        <option value="normal">⏰ Regular Hour</option>
                    </select>
                </div>
                
                <div class="mobile-form-group">
                    <label class="mobile-form-label">Price Per Person (HK$) <span class="mobile-required">*</span></label>
                    <input type="number" step="0.01" name="batch_trips[${batchTripCount}][price_per_person]" 
                           class="mobile-form-input batch-price" data-index="${batchTripCount}" 
                           min="0" value="250.00" required>
                </div>
                
                <div class="mobile-form-group batch-discount-field" data-index="${batchTripCount}" style="display: none;">
                    <label class="mobile-form-label">4-Person Group Discount (HK$)</label>
                    <input type="number" step="0.01" name="batch_trips[${batchTripCount}][four_person_discount]" 
                           class="mobile-form-input" min="0" value="50.00">
                </div>
                
                <div class="mobile-form-group">
                    <label class="mobile-form-label">Max People <span class="mobile-required">*</span></label>
                    <input type="number" name="batch_trips[${batchTripCount}][max_people]" 
                           class="mobile-form-input" min="1" max="10" value="4" required>
                </div>
            `;
        } else {
            tripCard.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-gray-800">Trip ${batchTripCount}</h4>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" 
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                        Remove
                    </button>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Departure Time <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="batch_trips[${batchTripCount}][departure_time]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time Slot Type <span class="text-red-500">*</span></label>
                        <select name="batch_trips[${batchTripCount}][type]" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 batch-time-slot" 
                                data-index="${batchTripCount}" required>
                            <option value="golden">🌟 Golden Hour</option>
                            <option value="normal">⏰ Regular Hour</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Per Person (HK$) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="batch_trips[${batchTripCount}][price_per_person]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 batch-price" 
                               data-index="${batchTripCount}" min="0" value="250.00" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max People <span class="text-red-500">*</span></label>
                        <input type="number" name="batch_trips[${batchTripCount}][max_people]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="1" max="10" value="4" required>
                    </div>
                </div>
                
                <div class="mt-4 batch-discount-field" data-index="${batchTripCount}" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-1">4-Person Group Discount (HK$)</label>
                    <input type="number" step="0.01" name="batch_trips[${batchTripCount}][four_person_discount]" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           min="0" value="50.00">
                </div>
            `;
        }
        
        container.appendChild(tripCard);
        
        // Add event listeners
        const timeSlotSelect = tripCard.querySelector('.batch-time-slot');
        const priceInput = tripCard.querySelector('.batch-price');
        const discountField = tripCard.querySelector('.batch-discount-field');
        
        timeSlotSelect.addEventListener('change', function() {
            const isGoldenHour = this.value === 'golden';
            if (isGoldenHour) {
                priceInput.value = '250.00';
                discountField.style.display = 'none';
            } else {
                priceInput.value = '275.00';
                discountField.style.display = 'block';
            }
        });
    }
    
    // Event listeners
    if (timeSlotSelect) {
        timeSlotSelect.addEventListener('change', updateBusinessRules);
    }
    
    // Creation mode toggle
    document.querySelectorAll('input[name="creation_mode"]').forEach(radio => {
        radio.addEventListener('change', updateCreationModeUI);
    });
    
    // Add trip buttons
    const addBatchTripBtn = document.getElementById('addBatchTripBtn');
    const desktopAddBatchTripBtn = document.getElementById('desktopAddBatchTripBtn');
    
    if (addBatchTripBtn) {
        addBatchTripBtn.addEventListener('click', () => {
            createBatchTripCard(document.getElementById('batchTripsContainer'), true);
        });
    }
    
    if (desktopAddBatchTripBtn) {
        desktopAddBatchTripBtn.addEventListener('click', () => {
            createBatchTripCard(document.getElementById('desktopBatchTripsContainer'), false);
        });
    }
    
    // Initial updates
    updateBusinessRules();
    updateCreationModeUI();
});
</script>
@endpush
