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
                    <input type="hidden" name="dropoff_location" value="前海華發冰雪世界">
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
                            前海華發冰雪世界
                        </div>
                    </div>

                    <!-- Batch Trip Form -->
                    <div id="batchTripForm">
                        <div class="mobile-info-box" style="margin-bottom: 16px;">
                            <p class="mobile-info-text">
                                <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                                Create multiple trips at once. Each trip will go to the same destination (前海華發冰雪世界).
                            </p>
                        </div>
                        
                        <div id="batchTripsContainer">
                            <!-- Batch trip items will be added here -->
                        </div>
                        
                        <button type="button" id="addBatchTripBtn" class="bg-green-500 text-white py-3 px-4 rounded-lg cursor-pointer w-full my-4 font-semibold">+ Add Another Trip</button>
                    </div>

                    <!-- Hidden field to always use batch mode -->
                    <input type="hidden" name="creation_mode" value="batch">

                    <!-- Submit Buttons -->
                    <div class="mobile-form-actions">
                        <button type="submit" class="mobile-action-btn mobile-btn-blue">
                            Create Trips
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
                    
                    <input type="hidden" name="dropoff_location" value="前海華發冰雪世界">
                  
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
                            前海華發冰雪世界
                        </div>
                    </div>

                    <!-- Batch Trip Form -->
                    <div id="desktopBatchTripForm">
                        <div class="mb-6 p-4 bg-blue-50 rounded-md border border-blue-100">
                            <p class="text-sm text-blue-800">
                                <i class="fa fa-info-circle mr-2"></i>
                                Create multiple trips at once. Each trip will go to the same destination (前海華發冰雪世界) but can have different departure times, time slots, and pricing.
                            </p>
                        </div>
                        
                        <div id="desktopBatchTripsContainer">
                            <!-- Batch trip items will be added here -->
                        </div>
                    
                        <button type="button" id="desktopAddBatchTripBtn" class="bg-green-600 text-white py-3 px-4 rounded-lg cursor-pointer w-full my-4 font-semibold">+ Add Another Trip</button>
                    </div>

                    <!-- Hidden field to always use batch mode -->
                    <input type="hidden" name="creation_mode" value="batch">

                    <!-- Submit button -->
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                            Create Trips
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
    let batchTripCount = 0;
    
    // Initialize batch forms on page load
    function initializeBatchForms() {
        const batchContainer = document.getElementById('batchTripsContainer');
        const desktopBatchContainer = document.getElementById('desktopBatchTripsContainer');
        
        if (batchContainer && batchContainer.children.length === 0) {
            createBatchTripCard(batchContainer, true);
            updateRemoveButtons(batchContainer);
        }
        if (desktopBatchContainer && desktopBatchContainer.children.length === 0) {
            createBatchTripCard(desktopBatchContainer, false);
            updateRemoveButtons(desktopBatchContainer);
        }
    }
    
    function updateRemoveButtons(container) {
        const tripCards = container.querySelectorAll('[data-trip-id]');
        
        tripCards.forEach(card => {
            const removeBtn = card.querySelector('.remove-trip-btn');
            if (tripCards.length <= 1) {
                // 只有一個 trip 時隱藏或禁用 Remove 按鈕
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = 'block';
            }
        });
    }
    
    function createBatchTripCard(container, isMobile = false) {
        // 計算實際應該的編號（基於現有卡片數量 + 1）
        const existingCards = container.querySelectorAll('[data-trip-id]');
        const actualTripNumber = existingCards.length + 1;
        
        const tripCard = document.createElement('div');
        tripCard.className = isMobile ? 'mobile-form-container' : 'mb-4 p-4 border border-gray-300 rounded-md bg-gray-50';
        tripCard.style.position = 'relative';
        tripCard.setAttribute('data-trip-id', actualTripNumber);
        
        if (isMobile) {
            tripCard.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h4 class="trip-title" style="font-weight: 600; color: #1f2937;">Trip ${actualTripNumber}</h4>
                    <button type="button" class="remove-trip-btn" style="
                        background: #ef4444; color: white; border: none; 
                        padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;
                    ">Remove</button>
                </div>
                
                <div class="mobile-form-group">
                    <label class="mobile-form-label">Departure Time <span class="mobile-required">*</span></label>
                    <input type="datetime-local" name="batch_trips[${actualTripNumber}][departure_time]" 
                           class="mobile-form-input" required>
                </div>
                
                <div class="mobile-form-group">
                    <label class="mobile-form-label">Time Slot Type <span class="mobile-required">*</span></label>
                    <select name="batch_trips[${actualTripNumber}][type]" 
                            class="mobile-form-select batch-time-slot" data-index="${actualTripNumber}" required>
                        <option value="golden">🌟 Golden Hour</option>
                        <option value="normal">⏰ Regular Hour</option>
                    </select>
                </div>
                
                <div class="mobile-form-group">
                    <label class="mobile-form-label">Price Per Person (HK$) <span class="mobile-required">*</span></label>
                    <input type="number" step="0.01" name="batch_trips[${actualTripNumber}][price_per_person]" 
                           class="mobile-form-input batch-price" data-index="${actualTripNumber}" 
                           min="0" value="250.00" required>
                </div>
                
                <div class="mobile-form-group batch-discount-field" data-index="${actualTripNumber}" style="display: none;">
                    <label class="mobile-form-label">4-Person Group Discount (HK$)</label>
                    <input type="number" step="0.01" name="batch_trips[${actualTripNumber}][four_person_discount]" 
                           class="mobile-form-input" min="0" value="50.00">
                </div>
                
                <div class="mobile-form-group">
                    <label class="mobile-form-label">Max People <span class="mobile-required">*</span></label>
                    <input type="number" name="batch_trips[${actualTripNumber}][max_people]" 
                           class="mobile-form-input" min="1" max="10" value="4" required>
                </div>
            `;
        } else {
            tripCard.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h4 class="trip-title font-semibold text-gray-800">Trip ${actualTripNumber}</h4>
                    <button type="button" class="remove-trip-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                        Remove
                    </button>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Departure Time <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="batch_trips[${actualTripNumber}][departure_time]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time Slot Type <span class="text-red-500">*</span></label>
                        <select name="batch_trips[${actualTripNumber}][type]" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 batch-time-slot" 
                                data-index="${actualTripNumber}" required>
                            <option value="golden">🌟 Golden Hour</option>
                            <option value="normal">⏰ Regular Hour</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Per Person (HK$) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="batch_trips[${actualTripNumber}][price_per_person]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 batch-price" 
                               data-index="${actualTripNumber}" min="0" value="250.00" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max People <span class="text-red-500">*</span></label>
                        <input type="number" name="batch_trips[${actualTripNumber}][max_people]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="1" max="10" value="4" required>
                    </div>
                </div>
                
                <div class="mt-4 batch-discount-field" data-index="${actualTripNumber}" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-1">4-Person Group Discount (HK$)</label>
                    <input type="number" step="0.01" name="batch_trips[${actualTripNumber}][four_person_discount]" 
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
        
        // Add remove functionality
        const removeBtn = tripCard.querySelector('.remove-trip-btn');
        removeBtn.addEventListener('click', function() {
            removeTripCard(this.closest('[data-trip-id]'));
        });
        
        // 更新全局計數器以確保始終跟踪最高的編號
        batchTripCount = Math.max(batchTripCount, actualTripNumber);
    }
    
    function removeTripCard(tripCard) {
        const container = tripCard.parentElement;
        
        // 檢查是否只剩一個 trip，如果是則不允許刪除
        const remainingTrips = container.querySelectorAll('[data-trip-id]');
        if (remainingTrips.length <= 1) {
            alert('At least one trip is required.');
            return;
        }
        
        // 移除卡片
        tripCard.remove();
        
        // 重新編號所有 trip
        renumberTrips(container);
        
        // 更新 Remove 按鈕狀態
        updateRemoveButtons(container);
    }
    
    function renumberTrips(container) {
        const tripCards = container.querySelectorAll('[data-trip-id]');
        
        tripCards.forEach((card, index) => {
            const newNumber = index + 1;
            
            // 更新標題
            const titleElement = card.querySelector('.trip-title');
            if (titleElement) {
                titleElement.textContent = `Trip ${newNumber}`;
            }
            
            // 更新所有 input name 屬性
            const inputs = card.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.name && input.name.includes('batch_trips[')) {
                    const namePattern = /batch_trips\[\d+\]/;
                    input.name = input.name.replace(namePattern, `batch_trips[${newNumber}]`);
                }
            });
            
            // 更新 data attributes
            card.setAttribute('data-trip-id', newNumber);
            const elementsWithDataIndex = card.querySelectorAll('[data-index]');
            elementsWithDataIndex.forEach(element => {
                element.setAttribute('data-index', newNumber);
            });
        });
        
        // 重置全局計數器為當前卡片數量，確保下次添加的編號是連續的
        batchTripCount = tripCards.length;
    }
    
    // Event listeners
    
    // Add trip buttons
    const addBatchTripBtn = document.getElementById('addBatchTripBtn');
    const desktopAddBatchTripBtn = document.getElementById('desktopAddBatchTripBtn');
    
    if (addBatchTripBtn) {
        addBatchTripBtn.addEventListener('click', () => {
            const container = document.getElementById('batchTripsContainer');
            createBatchTripCard(container, true);
            updateRemoveButtons(container);
        });
    }
    
    if (desktopAddBatchTripBtn) {
        desktopAddBatchTripBtn.addEventListener('click', () => {
            const container = document.getElementById('desktopBatchTripsContainer');
            createBatchTripCard(container, false);
            updateRemoveButtons(container);
        });
    }
    
    // Initial setup
    initializeBatchForms();
});
</script>
@endpush
