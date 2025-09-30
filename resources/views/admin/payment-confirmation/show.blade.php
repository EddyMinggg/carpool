@extends('admin.layout')

@section('title', 'Confirm Payment - ' . $payment->user->username)
@section('page-title', 'Confirm Payment')

@section('content')
    {{-- Mobile CSS Reset and Styles --}}
    @if($isMobile)
        <style>
            /* Mobile strict CSS reset */
            * {
                box-sizing: border-box !important;
                max-width: 100vw !important;
            }
            
            html, body {
                overflow-x: hidden !important;
                width: 100vw !important;
                max-width: 100vw !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .container, .container-fluid, .row, .col, [class*="col-"] {
                width: 100vw !important;
                max-width: 100vw !important;
                margin: 0 !important;
                padding: 12px !important;
                overflow-x: hidden !important;
            }
            
            /* Mobile card styles */
            .mobile-card {
                background: white;
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            
            .mobile-section-title {
                font-size: 18px;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 16px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .mobile-info-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .mobile-info-item {
                padding-bottom: 12px;
                border-bottom: 1px solid #f3f4f6;
            }
            
            .mobile-info-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }
            
            .mobile-info-label {
                font-size: 12px;
                color: #6b7280;
                text-transform: uppercase;
                font-weight: 600;
                margin-bottom: 4px;
            }
            
            .mobile-info-value {
                font-size: 16px;
                color: #1f2937;
                font-weight: 500;
            }
            
            .mobile-amount {
                font-size: 24px !important;
                font-weight: 700 !important;
                color: #3b82f6 !important;
            }
            
            .mobile-payment-badge {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                text-align: center;
                display: inline-block;
            }
            
            .mobile-payment-badge.deposit {
                background: #fed7aa;
                color: #9a3412;
                border: 2px solid #fb923c;
            }
            
            .mobile-payment-badge.remaining {
                background: #c7d2fe;
                color: #3730a3;
                border: 2px solid #6366f1;
            }
            
            .mobile-form-group {
                margin-bottom: 20px;
            }
            
            .mobile-form-label {
                font-size: 14px;
                font-weight: 600;
                color: #374151;
                margin-bottom: 8px;
                display: block;
            }
            
            .mobile-form-input {
                width: 100%;
                padding: 12px 16px;
                border: 2px solid #e5e7eb;
                border-radius: 8px;
                font-size: 16px;
                background: white;
                transition: all 0.2s;
            }
            
            .mobile-form-input:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            
            .mobile-form-textarea {
                width: 100%;
                padding: 12px 16px;
                border: 2px solid #e5e7eb;
                border-radius: 8px;
                font-size: 16px;
                background: white;
                min-height: 100px;
                resize: vertical;
                transition: all 0.2s;
            }
            
            .mobile-form-textarea:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            
            .mobile-help-text {
                font-size: 12px;
                color: #6b7280;
                margin-top: 4px;
            }
            
            .mobile-info-box {
                background: #eff6ff;
                border: 2px solid #bfdbfe;
                border-radius: 12px;
                padding: 16px;
                margin: 16px 0;
            }
            
            .mobile-info-title {
                font-size: 14px;
                font-weight: 600;
                color: #1e40af;
                margin-bottom: 8px;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            
            .mobile-info-text {
                font-size: 13px;
                color: #1e40af;
                margin-bottom: 8px;
            }
            
            .mobile-info-list {
                font-size: 12px;
                color: #2563eb;
                margin: 0;
                padding-left: 16px;
            }
            
            .mobile-checkbox-container {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                margin: 20px 0;
                padding: 16px;
                background: #f9fafb;
                border-radius: 8px;
                border: 2px solid #e5e7eb;
            }
            
            .mobile-checkbox {
                margin-top: 2px;
                width: 18px;
                height: 18px;
                accent-color: #3b82f6;
            }
            
            .mobile-checkbox-label {
                font-size: 13px;
                color: #374151;
                line-height: 1.4;
            }
            
            .mobile-action-buttons {
                display: grid;
                grid-template-columns: 1fr 2fr;
                gap: 12px;
                margin-top: 24px;
                padding-top: 20px;
                border-top: 2px solid #f3f4f6;
            }
            
            .mobile-btn {
                padding: 14px 20px;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 600;
                text-align: center;
                text-decoration: none;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
            
            .mobile-btn-secondary {
                background: #f3f4f6;
                color: #374151;
                border: 2px solid #d1d5db;
            }
            
            .mobile-btn-secondary:hover {
                background: #e5e7eb;
                color: #374151;
            }
            
            .mobile-btn-primary {
                background: linear-gradient(135deg, #10b981, #059669);
                color: white;
                border: 2px solid #10b981;
            }
            
            .mobile-btn-primary:hover {
                background: linear-gradient(135deg, #059669, #047857);
                color: white;
            }
            
            .mobile-quick-actions {
                background: #f8fafc;
                border-radius: 12px;
                padding: 16px;
                margin: 16px 0 80px 0;
            }
            
            .mobile-quick-title {
                font-size: 14px;
                font-weight: 600;
                color: #374151;
                margin-bottom: 12px;
            }
            
            .mobile-quick-buttons {
                display: grid;
                grid-template-columns: 1fr;
                gap: 8px;
            }
            
            .mobile-quick-btn {
                padding: 10px 12px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 500;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: all 0.2s;
            }
            
            .mobile-quick-btn.blue {
                background: #dbeafe;
                color: #1e40af;
                border: 1px solid #bfdbfe;
            }
            
            .mobile-quick-btn.purple {
                background: #e0e7ff;
                color: #3730a3;
                border: 1px solid #c7d2fe;
            }
            
            .mobile-quick-btn.green {
                background: #dcfce7;
                color: #166534;
                border: 1px solid #bbf7d0;
            }
            
            .mobile-quick-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            
            .mobile-back-btn {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 56px;
                height: 56px;
                background: linear-gradient(135deg, #6b7280, #4b5563);
                color: white;
                border-radius: 50%;
                border: none;
                font-size: 20px;
                box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
                cursor: pointer;
                z-index: 1000;
                display: flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                transition: all 0.3s;
            }
            
            .mobile-back-btn:hover {
                transform: scale(1.05);
                color: white;
            }
            
            /* Hide desktop elements on mobile */
            .desktop-only {
                display: none !important;
            }
        </style>
    @endif
    
    <style>
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
            <!-- Mobile Header -->
            <div class="mobile-card">
                <div class="mobile-section-title">
                    💰 Confirm Payment
                </div>
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">
                    User: {{ $payment->user->username }}
                </div>
                <div class="mobile-payment-badge {{ $payment->type }}">
                    {{ $payment->type === 'deposit' ? '💰 DEPOSIT (20%)' : '💳 REMAINING (80%)' }}
                </div>
            </div>

            @if (session('success'))
                <div style="background: #dcfce7; border: 2px solid #bbf7d0; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background: #fecaca; border: 2px solid #f87171; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                    @foreach ($errors->all() as $error)
                        <div style="font-size: 14px;">• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- User Information -->
            <div class="mobile-card">
                <div class="mobile-section-title">
                    👤 User Information
                </div>
                <div class="mobile-info-grid">
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Username</div>
                        <div class="mobile-info-value">{{ $payment->user->username }}</div>
                    </div>
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Email</div>
                        <div class="mobile-info-value">{{ $payment->user->email }}</div>
                    </div>
                    @if($payment->user->phone_number)
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Phone</div>
                        <div class="mobile-info-value">{{ $payment->user->phone_number }}</div>
                    </div>
                    @endif
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Payment Created</div>
                        <div class="mobile-info-value">{{ $payment->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Trip Information -->
            <div class="mobile-card">
                <div class="mobile-section-title">
                    🚗 Trip Information
                </div>
                <div class="mobile-info-grid">
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Trip ID</div>
                        <div class="mobile-info-value">#{{ $payment->trip->id }}</div>
                    </div>
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Destination</div>
                        <div class="mobile-info-value">{{ $payment->trip->dropoff_location }}</div>
                    </div>
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Departure Time</div>
                        <div class="mobile-info-value">{{ $payment->trip->planned_departure_time->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Payment Amount</div>
                        <div class="mobile-info-value mobile-amount">HK$ {{ number_format($payment->amount, 2) }}</div>
                    </div>
                    @if($payment->pickup_location)
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Pickup Location</div>
                        <div class="mobile-info-value">{{ $payment->pickup_location }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Confirmation Form -->
            <div class="mobile-card">
                <div class="mobile-section-title">
                    ✅ Confirm Payment Receipt
                </div>
                
                <form action="{{ route('admin.payment-confirmation.confirm', $payment) }}" method="POST">
                    @csrf

                    <!-- Reference Code -->
                    <div class="mobile-form-group">
                        <label for="reference_code" class="mobile-form-label">
                            Payment Reference Code *
                        </label>
                        <input type="text" 
                               name="reference_code" 
                               id="reference_code" 
                               value="{{ old('reference_code') }}"
                               placeholder="Enter reference code"
                               class="mobile-form-input"
                               required>
                        <div class="mobile-help-text">
                            Enter the reference code from the user's payment proof
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="mobile-form-group">
                        <label for="notes" class="mobile-form-label">
                            Additional Notes (Optional)
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  placeholder="Any additional notes..."
                                  class="mobile-form-textarea">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Email Notification Preview -->
                    <div class="mobile-info-box">
                        <div class="mobile-info-title">
                            📧 Email Notification Preview
                        </div>
                        <div class="mobile-info-text">
                            After confirming, an email will be sent to <strong>{{ $payment->user->email }}</strong> with:
                        </div>
                        <ul class="mobile-info-list">
                            <li>{{ ucfirst($payment->type) }} payment confirmation for trip #{{ $payment->trip->id }}</li>
                            <li>Payment amount: HK$ {{ number_format($payment->amount, 2) }}</li>
                            <li>Trip details and departure time</li>
                            <li>Further instructions for the trip</li>
                        </ul>
                    </div>

                    <!-- Confirmation Checkbox -->
                    <div class="mobile-checkbox-container">
                        <input type="checkbox" 
                               name="confirm_payment" 
                               id="confirm_payment" 
                               value="1"
                               class="mobile-checkbox"
                               required>
                        <label for="confirm_payment" class="mobile-checkbox-label">
                            I confirm that I have verified the payment proof and the payment amount is correct. An email notification will be sent to the user.
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mobile-action-buttons">
                        <a href="{{ route('admin.payment-confirmation.index', $payment->trip) }}" 
                           class="mobile-btn mobile-btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="mobile-btn mobile-btn-primary">
                            ✅ Confirm & Send Email
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Actions -->
            <div class="mobile-quick-actions">
                <div class="mobile-quick-title">Quick Actions</div>
                <div class="mobile-quick-buttons">
                    <a href="{{ route('admin.trips.show', $payment->trip) }}" class="mobile-quick-btn blue">
                        🚗 View Trip Details
                    </a>
                    <a href="{{ route('admin.users.show', $payment->user) }}" class="mobile-quick-btn purple">
                        👤 View User Profile
                    </a>
                    <a href="{{ route('admin.payment-confirmation.index', $payment->trip) }}" class="mobile-quick-btn green">
                        💰 All Trip Payments
                    </a>
                </div>
            </div>

            <!-- Mobile Back Button -->
            <a href="{{ route('admin.payment-confirmation.index', $payment->trip) }}" class="mobile-back-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    @else
        <!-- Desktop Layout -->
        <div class="desktop-only">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Confirm Payment</h2>
                    <p class="text-gray-600">User: {{ $payment->user->username }} ({{ ucfirst($payment->type) }})</p>
                </div>
                <a href="{{ route('admin.payment-confirmation.index', $payment->trip) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Back to List
                </a>
            </div>

            <div class="max-w-4xl mx-auto">
            @if (session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-100 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- User & Trip Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Trip & User Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Info -->
                        <div class="space-y-4">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300">User Information</h4>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Username</label>
                                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $payment->user->username }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->user->email }}</p>
                            </div>
                            @if($payment->user->phone_number)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Phone</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->user->phone_number }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Type</label>
                                <p class="text-gray-900 dark:text-gray-100">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full border-2 {{ $payment->type === 'deposit' ? 'bg-orange-200 text-orange-900 border-orange-400 dark:bg-orange-800 dark:text-orange-100 dark:border-orange-500' : 'bg-indigo-200 text-indigo-900 border-indigo-400 dark:bg-indigo-800 dark:text-indigo-100 dark:border-indigo-500' }}">
                                        {{ $payment->type === 'deposit' ? '💰 DEPOSIT (20%)' : '💳 REMAINING (80%)' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Created</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>

                        <!-- Trip Info -->
                        <div class="space-y-4">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300">Trip Information</h4>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Trip ID</label>
                                <p class="text-gray-900 dark:text-gray-100 font-medium">#{{ $payment->trip->id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Destination</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->trip->dropoff_location }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Departure Time</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->trip->planned_departure_time->format('Y-m-d H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Amount</label>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">HK$ {{ number_format($payment->amount, 2) }}</p>
                            </div>
                            @if($payment->pickup_location)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Pickup Location</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->pickup_location }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Confirmation Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Confirm Payment Receipt</h3>
                    
                    <form action="{{ route('admin.payment-confirmation.confirm', $payment) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Reference Code -->
                        <div>
                            <label for="reference_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Payment Reference Code *
                            </label>
                            <input type="text" 
                                   name="reference_code" 
                                   id="reference_code" 
                                   value="{{ old('reference_code') }}"
                                   placeholder="Enter bank transfer reference or transaction ID"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200"
                                   required>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Enter the reference code from the user's payment proof (bank transfer, e-wallet, etc.)
                            </p>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Additional Notes (Optional)
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3"
                                      placeholder="Any additional notes about this payment confirmation"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Email Notification Preview -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 dark:text-blue-200 mb-2">📧 Email Notification Preview</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                After confirming, an email will be sent to <strong>{{ $payment->user->email }}</strong> with:
                            </p>
                            <ul class="mt-2 text-sm text-blue-600 dark:text-blue-400 space-y-1">
                                <li>• {{ ucfirst($payment->type) }} payment confirmation for trip #{{ $payment->trip->id }}</li>
                                <li>• Payment amount: HK$ {{ number_format($payment->amount, 2) }}</li>
                                <li>• Trip details and departure time</li>
                                <li>• Further instructions for the trip</li>
                            </ul>
                        </div>

                        <!-- Confirmation Checkbox -->
                        <div class="flex items-start">
                            <input type="checkbox" 
                                   name="confirm_payment" 
                                   id="confirm_payment" 
                                   value="1"
                                   class="mt-1 rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                                   required>
                            <label for="confirm_payment" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                I confirm that I have verified the payment proof and the payment amount is correct. An email notification will be sent to the user.
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.payment-confirmation.index', $payment->trip) }}" 
                               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                ✅ Confirm Payment & Send Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3">Quick Actions</h4>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.trips.show', $payment->trip) }}" 
                       class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 text-sm rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900 transition">
                        🚗 View Trip Details
                    </a>
                    <a href="{{ route('admin.users.show', $payment->user) }}" 
                       class="inline-flex items-center px-3 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200 text-sm rounded-lg hover:bg-purple-200 dark:hover:bg-purple-900 transition">
                        👤 View User Profile
                    </a>
                    <a href="{{ route('admin.payment-confirmation.index', $payment->trip) }}" 
                       class="inline-flex items-center px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 text-sm rounded-lg hover:bg-green-200 dark:hover:bg-green-900 transition">
                        💰 All Trip Payments
                    </a>
                </div>
            </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const referenceInput = document.getElementById('reference_code');
            const confirmCheckbox = document.getElementById('confirm_payment');
            
            // Auto-generate reference code suggestion
            function generateReferenceCode() {
                if (!referenceInput.value) {
                    const now = new Date();
                    const timestamp = now.toISOString().slice(2, 10).replace(/-/g, '');
                    const tripId = "{{ $payment->trip->id }}";
                    const userId = "{{ $payment->user->id }}";
                    const paymentId = "{{ $payment->id }}";
                    referenceInput.value = `REF${tripId}${userId}P${paymentId}${timestamp}`;
                }
            }

            // Focus on reference code input
            referenceInput.focus();

            // Generate reference code suggestion on focus if empty
            referenceInput.addEventListener('focus', generateReferenceCode);

            // Form validation
            form.addEventListener('submit', function(e) {
                if (!confirmCheckbox.checked) {
                    e.preventDefault();
                    alert('Please confirm that you have verified the payment proof.');
                    confirmCheckbox.focus();
                    return false;
                }

                if (!referenceInput.value.trim()) {
                    e.preventDefault();
                    alert('Please enter a payment reference code.');
                    referenceInput.focus();
                    return false;
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '⏳ Confirming Payment...';
            });
        });
    </script>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const referenceInput = document.getElementById('reference_code');
            const confirmCheckbox = document.getElementById('confirm_payment');
            
            // Auto-generate reference code suggestion
            function generateReferenceCode() {
                if (!referenceInput.value) {
                    const now = new Date();
                    const timestamp = now.toISOString().slice(2, 10).replace(/-/g, '');
                    const tripId = "{{ $payment->trip->id }}";
                    const userId = "{{ $payment->user->id }}";
                    const paymentId = "{{ $payment->id }}";
                    referenceInput.value = `REF${tripId}${userId}P${paymentId}${timestamp}`;
                }
            }

            // Focus on reference code input
            referenceInput.focus();

            // Generate reference code suggestion on focus if empty
            referenceInput.addEventListener('focus', generateReferenceCode);

            // Form validation
            form.addEventListener('submit', function(e) {
                if (!confirmCheckbox.checked) {
                    e.preventDefault();
                    alert('Please confirm that you have verified the payment proof.');
                    confirmCheckbox.focus();
                    return false;
                }

                if (!referenceInput.value.trim()) {
                    e.preventDefault();
                    alert('Please enter a payment reference code.');
                    referenceInput.focus();
                    return false;
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '⏳ Confirming Payment...';
                }
            });
        });
    </script>
@endsection