@extends('admin.layout')

@section('title', 'Payment Confirmation - ' . $trip->dropoff_location)
@section('page-title', 'Payment Confirmation')

@section('content')
    {{-- Mobile CSS Reset and Styles --}}
    @if($isMobile)
        <style>
            /* Mobile strict CSS reset - prevent horizontal scrolling */
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
            
            /* Mobile stat cards */
            .mobile-stats-grid {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 12px !important;
                margin-bottom: 16px !important;
            }
            
            .mobile-stat-card {
                background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
                color: white;
                border-radius: 12px;
                padding: 12px;
                text-align: center;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            
            .mobile-stat-card.amber { --gradient-start: #f59e0b; --gradient-end: #d97706; }
            .mobile-stat-card.emerald { --gradient-start: #10b981; --gradient-end: #059669; }
            .mobile-stat-card.sky { --gradient-start: #0ea5e9; --gradient-end: #0284c7; }
            .mobile-stat-card.violet { --gradient-start: #8b5cf6; --gradient-end: #7c3aed; }
            .mobile-stat-card.orange { --gradient-start: #f97316; --gradient-end: #ea580c; }
            .mobile-stat-card.indigo { --gradient-start: #6366f1; --gradient-end: #4f46e5; }
            
            .mobile-stat-number {
                font-size: 20px;
                font-weight: 700;
                margin-bottom: 4px;
            }
            
            .mobile-stat-label {
                font-size: 11px;
                opacity: 0.9;
                text-transform: uppercase;
                font-weight: 600;
            }
            
            /* Mobile payment cards */
            .mobile-payment-card {
                background: white;
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                border: none;
                overflow: hidden;
            }
            
            .mobile-payment-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 12px;
            }
            
            .mobile-user-info {
                flex: 1;
            }
            
            .mobile-username {
                font-size: 16px;
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 4px;
            }
            
            .mobile-email {
                font-size: 12px;
                color: #6b7280;
            }
            
            .mobile-payment-badge {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                text-align: center;
                min-width: 80px;
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
            
            .mobile-payment-details {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                margin: 12px 0;
            }
            
            .mobile-detail-item {
                padding: 8px;
                background: #f8fafc;
                border-radius: 8px;
                text-align: center;
            }
            
            .mobile-detail-label {
                font-size: 10px;
                color: #6b7280;
                text-transform: uppercase;
                font-weight: 600;
                margin-bottom: 4px;
            }
            
            .mobile-detail-value {
                font-size: 14px;
                font-weight: 600;
                color: #1f2937;
            }
            
            .mobile-action-btn {
                width: 100%;
                padding: 12px;
                background: linear-gradient(135deg, #3b82f6, #2563eb);
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                text-align: center;
                text-decoration: none;
                display: block;
                transition: all 0.2s;
                margin-top: 12px;
            }
            
            .mobile-action-btn:hover, .mobile-action-btn:active {
                background: linear-gradient(135deg, #2563eb, #1d4ed8);
                color: white;
                transform: translateY(-1px);
                text-decoration: none;
            }
            
            .mobile-section-header {
                background: white;
                padding: 16px;
                border-radius: 12px;
                margin-bottom: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            
            .mobile-section-title {
                font-size: 18px;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 4px;
            }
            
            .mobile-section-subtitle {
                font-size: 14px;
                color: #6b7280;
            }
            
            .mobile-empty-state {
                text-align: center;
                padding: 40px 20px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            
            .mobile-empty-icon {
                font-size: 48px;
                margin-bottom: 16px;
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
            <div class="mobile-section-header">
                <div class="mobile-section-title">Payment Confirmation</div>
                <div class="mobile-section-subtitle">Trip: {{ $trip->dropoff_location }}</div>
                <div style="font-size: 12px; color: #9ca3af; margin-top: 4px;">
                    Departure: {{ $trip->planned_departure_time->format('M d, H:i') }}
                </div>
            </div>

            <!-- Mobile Statistics Grid -->
            <div class="mobile-stats-grid">
                <div class="mobile-stat-card amber">
                    <div class="mobile-stat-number">{{ $pendingPayments->count() }}</div>
                    <div class="mobile-stat-label">Pending</div>
                </div>
                <div class="mobile-stat-card emerald">
                    <div class="mobile-stat-number">{{ $confirmedPayments->count() }}</div>
                    <div class="mobile-stat-label">Confirmed</div>
                </div>
                <div class="mobile-stat-card sky">
                    <div class="mobile-stat-number">{{ $tripStats['total_members'] }}</div>
                    <div class="mobile-stat-label">Members</div>
                </div>
                <div class="mobile-stat-card violet">
                    <div class="mobile-stat-number">HK$ {{ number_format($tripStats['total_confirmed_amount'], 0) }}</div>
                    <div class="mobile-stat-label">Total</div>
                </div>
            </div>

            <!-- Payment Type Breakdown -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div class="mobile-stat-card orange">
                    <div style="font-size: 12px; margin-bottom: 8px; opacity: 0.9;">💰 Deposits</div>
                    <div style="display: flex; justify-content: space-between;">
                        <div style="text-align: center;">
                            <div style="font-size: 16px; font-weight: 700;">{{ $tripStats['confirmed_deposits'] }}</div>
                            <div style="font-size: 9px; opacity: 0.8;">Confirmed</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 16px; font-weight: 700;">{{ $tripStats['pending_deposits'] }}</div>
                            <div style="font-size: 9px; opacity: 0.8;">Pending</div>
                        </div>
                    </div>
                </div>
                <div class="mobile-stat-card indigo">
                    <div style="font-size: 12px; margin-bottom: 8px; opacity: 0.9;">💳 Remaining</div>
                    <div style="display: flex; justify-content: space-between;">
                        <div style="text-align: center;">
                            <div style="font-size: 16px; font-weight: 700;">{{ $tripStats['confirmed_remaining'] }}</div>
                            <div style="font-size: 9px; opacity: 0.8;">Confirmed</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 16px; font-weight: 700;">{{ $tripStats['pending_remaining'] }}</div>
                            <div style="font-size: 9px; opacity: 0.8;">Pending</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            @if($pendingPayments->count() > 0)
                <div style="margin-bottom: 16px;">
                    <div style="background: white; border-radius: 12px; padding: 12px; margin-bottom: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <div style="font-size: 16px; font-weight: 600; color: #f59e0b; margin-bottom: 4px;">
                            🕐 Pending Payments ({{ $pendingPayments->count() }})
                        </div>
                        <div style="font-size: 12px; color: #6b7280;">
                            Tap any payment to confirm
                        </div>
                    </div>
                    
                    @foreach($pendingPayments as $payment)
                        <div class="mobile-payment-card">
                            <div class="mobile-payment-header">
                                <div class="mobile-user-info">
                                    <div class="mobile-username">
                                        {{ $payment->user_phone }}
                                        @if($payment->type === 'group_full_payment' && $payment->group_size > 1)
                                            <span style="font-size: 10px; background: #dbeafe; color: #1e40af; padding: 2px 6px; border-radius: 10px; margin-left: 6px;">
                                                GROUP ({{ $payment->group_size }} people)
                                            </span>
                                        @endif
                                    </div>
                                    {{-- <div class="mobile-email">{{ $payment->user->email }}</div> --}}
                                </div>
                                <div class="mobile-payment-badge {{ $payment->type === 'group_full_payment' ? 'deposit' : $payment->type }}">
                                    @if($payment->type === 'group_full_payment')
                                        👥 GROUP
                                    @else
                                        {{ $payment->type === 'deposit' ? '💰 DEPOSIT' : '💳 REMAINING' }}
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mobile-payment-details">
                                <div class="mobile-detail-item">
                                    <div class="mobile-detail-label">
                                        @if($payment->type === 'group_full_payment')
                                            Total Amount
                                        @else
                                            Amount
                                        @endif
                                    </div>
                                    <div class="mobile-detail-value">HK$ {{ number_format($payment->amount, 2) }}</div>
                                </div>
                                <div class="mobile-detail-item">
                                    <div class="mobile-detail-label">Created</div>
                                    <div class="mobile-detail-value">{{ $payment->created_at->format('M d') }}</div>
                                </div>
                            </div>
                            
                            @if($payment->type === 'group_full_payment' && $payment->childPayments()->exists())
                                <!-- Group Booking Details -->
                                <div style="background: #eff6ff; padding: 8px; border-radius: 6px; margin: 8px 0; border: 1px solid #bfdbfe;">
                                    <div style="font-size: 10px; color: #1e40af; margin-bottom: 6px; font-weight: 600;">👥 GROUP PASSENGERS</div>
                                    @php $allGroupPayments = collect([$payment])->merge($payment->childPayments); @endphp
                                    @foreach($allGroupPayments as $index => $groupPayment)
                                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 4px 0; {{ !$loop->last ? 'border-bottom: 1px solid #dbeafe;' : '' }}">
                                            <div style="font-size: 11px; color: #1f2937;">
                                                <strong>{{ $index + 1 }}.</strong> {{ $groupPayment->user_phone }}
                                                @if($index === 0)<span style="color: #059669; font-weight: 600;"> (Main)</span>@endif
                                            </div>
                                            <div style="font-size: 10px; color: #6b7280;">
                                                HK$ {{ number_format($groupPayment->amount, 0) }}
                                            </div>
                                        </div>
                                        @php
                                            $groupTripJoin = $groupPayment->tripJoins->first();
                                        @endphp
                                        @if($groupTripJoin && $groupTripJoin->pickup_location)
                                            <div style="font-size: 9px; color: #6b7280; margin-left: 12px; margin-top: 2px;">
                                                📍 {{ Str::limit($groupTripJoin->pickup_location, 25) }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                @php
                                    $mainTripJoin = $payment->tripJoins->first();
                                @endphp
                                @if($mainTripJoin && $mainTripJoin->pickup_location)
                                    <div style="background: #f8fafc; padding: 8px; border-radius: 6px; margin: 8px 0;">
                                        <div style="font-size: 10px; color: #6b7280; margin-bottom: 2px;">PICKUP LOCATION</div>
                                        <div style="font-size: 12px; color: #374151;">{{ $mainTripJoin->pickup_location }}</div>
                                    </div>
                                @endif
                            @endif
                            
                            <a href="{{ route('admin.payment-confirmation.show', $payment) }}" class="mobile-action-btn">
                                Confirm Payment
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Confirmed Payments -->
            @if($confirmedPayments->count() > 0)
                <div style="margin-bottom: 80px;">
                    <div style="background: white; border-radius: 12px; padding: 12px; margin-bottom: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <div style="font-size: 16px; font-weight: 600; color: #10b981; margin-bottom: 4px;">
                            ✅ Confirmed Payments ({{ $confirmedPayments->count() }})
                        </div>
                        <div style="font-size: 12px; color: #6b7280;">
                            Successfully processed payments
                        </div>
                    </div>
                    
                    @foreach($confirmedPayments as $payment)
                        <div class="mobile-payment-card" style="border: 2px solid #dcfce7;">
                            <div class="mobile-payment-header">
                                <div class="mobile-user-info">
                                    <div class="mobile-username">{{ $payment->user_phone }}</div>
                                    {{-- <div class="mobile-email">{{ $payment->user->email }}</div> --}}
                                </div>
                                <div class="mobile-payment-badge {{ $payment->type }}">
                                    {{ $payment->type === 'deposit' ? '💰 DEPOSIT' : '💳 REMAINING' }}
                                </div>
                            </div>
                            
                            <div class="mobile-payment-details">
                                <div class="mobile-detail-item">
                                    <div class="mobile-detail-label">Amount</div>
                                    <div class="mobile-detail-value">HK$ {{ number_format($payment->amount, 2) }}</div>
                                </div>
                                <div class="mobile-detail-item">
                                    <div class="mobile-detail-label">Confirmed</div>
                                    <div class="mobile-detail-value">{{ $payment->updated_at->format('M d') }}</div>
                                </div>
                            </div>
                            
                            <div style="background: #f0fdf4; padding: 8px; border-radius: 6px; margin-top: 8px;">
                                <div style="font-size: 10px; color: #16a34a; margin-bottom: 2px; font-weight: 600;">✅ REFERENCE CODE</div>
                                <div style="font-size: 14px; color: #15803d; font-family: monospace; font-weight: 600;">{{ $payment->reference_code }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Empty State -->
            @if($pendingPayments->count() === 0 && $confirmedPayments->count() === 0)
                <div class="mobile-empty-state">
                    <div class="mobile-empty-icon">📭</div>
                    <div style="font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 8px;">No Payment Records</div>
                    <div style="font-size: 14px; color: #6b7280;">There are no payment records for this trip yet.</div>
                </div>
            @endif

            <!-- Mobile Back Button -->
            <a href="{{ route('admin.trips.show', $trip) }}" class="mobile-back-btn">
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
                    <h2 class="text-2xl font-bold text-gray-800">Payment Confirmation</h2>
                    <p class="text-gray-600">Trip: {{ $trip->dropoff_location }}</p>
                </div>
                <a href="{{ route('admin.trips.show', $trip) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Back to Trip
                </a>
            </div>
        </div>
    @endif

    <div class="desktop-only">
            <!-- Trip Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Trip Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Trip ID</label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $trip->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Destination</label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $trip->dropoff_location }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Departure</label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $trip->planned_departure_time->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-amber-100 dark:bg-amber-900/30 border-2 border-amber-300 dark:border-amber-600 p-4 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-amber-900 dark:text-amber-100">{{ $pendingPayments->count() }}</div>
                            <div class="text-sm font-medium text-amber-700 dark:text-amber-300">Pending Payments</div>
                        </div>
                        <div class="bg-emerald-100 dark:bg-emerald-900/30 border-2 border-emerald-300 dark:border-emerald-600 p-4 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-emerald-900 dark:text-emerald-100">{{ $confirmedPayments->count() }}</div>
                            <div class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Confirmed Payments</div>
                        </div>
                        <div class="bg-sky-100 dark:bg-sky-900/30 border-2 border-sky-300 dark:border-sky-600 p-4 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-sky-900 dark:text-sky-100">{{ $tripStats['total_members'] }}</div>
                            <div class="text-sm font-medium text-sky-700 dark:text-sky-300">Total Members</div>
                        </div>
                        <div class="bg-violet-100 dark:bg-violet-900/30 border-2 border-violet-300 dark:border-violet-600 p-4 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-violet-900 dark:text-violet-100">HK$ {{ number_format($tripStats['total_confirmed_amount'], 2) }}</div>
                            <div class="text-sm font-medium text-violet-700 dark:text-violet-300">Total Confirmed</div>
                        </div>
                    </div>
                    
                    <!-- Payment Type Breakdown -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="bg-orange-100 dark:bg-orange-900/40 border-2 border-orange-300 dark:border-orange-500 p-4 rounded-lg shadow-sm">
                            <div class="text-lg font-bold text-orange-900 dark:text-orange-100 mb-3">💰 Deposits (20%)</div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-600 rounded-lg p-2">
                                    <div class="text-xl font-bold text-emerald-800 dark:text-emerald-100">{{ $tripStats['confirmed_deposits'] }}</div>
                                    <div class="text-xs font-medium text-emerald-700 dark:text-emerald-300">Confirmed</div>
                                </div>
                                <div class="text-center bg-amber-50 dark:bg-amber-900/50 border border-amber-200 dark:border-amber-600 rounded-lg p-2">
                                    <div class="text-xl font-bold text-amber-800 dark:text-amber-100">{{ $tripStats['pending_deposits'] }}</div>
                                    <div class="text-xs font-medium text-amber-700 dark:text-amber-300">Pending</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-indigo-100 dark:bg-indigo-900/40 border-2 border-indigo-300 dark:border-indigo-500 p-4 rounded-lg shadow-sm">
                            <div class="text-lg font-bold text-indigo-900 dark:text-indigo-100 mb-3">💳 Remaining (80%)</div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-600 rounded-lg p-2">
                                    <div class="text-xl font-bold text-emerald-800 dark:text-emerald-100">{{ $tripStats['confirmed_remaining'] }}</div>
                                    <div class="text-xs font-medium text-emerald-700 dark:text-emerald-300">Confirmed</div>
                                </div>
                                <div class="text-center bg-amber-50 dark:bg-amber-900/50 border border-amber-200 dark:border-amber-600 rounded-lg p-2">
                                    <div class="text-xl font-bold text-amber-800 dark:text-amber-100">{{ $tripStats['pending_remaining'] }}</div>
                                    <div class="text-xs font-medium text-amber-700 dark:text-amber-300">Pending</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Payment Confirmations -->
            @if($pendingPayments->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            🕐 Pending Payment Confirmations ({{ $pendingPayments->count() }})
                        </h3>
                        <button onclick="toggleBulkConfirm()" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition text-sm">
                            Bulk Confirm
                        </button>
                    </div>
                </div>

                <!-- Bulk Confirm Form (Hidden by default) -->
                <div id="bulk-confirm-form" class="hidden border-b border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900">
                    <form action="{{ route('admin.payment-confirmation.bulk-confirm', $trip) }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            @foreach($pendingPayments as $payment)
                            <div class="flex items-center space-x-3 p-3 bg-white dark:bg-gray-800 rounded-lg">
                                <input type="checkbox" name="selected_payments[]" value="{{ $payment->id }}" 
                                       class="bulk-checkbox rounded border-gray-300 dark:border-gray-600">
                                <div class="flex-1">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $payment->user_phone }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">HK$ {{ number_format($payment->amount, 2) }}</span>
                                </div>
                                <input type="hidden" name="confirmations[{{ $loop->index }}][trip_join_id]" value="{{ $payment->id }}">
                                <input type="text" name="confirmations[{{ $loop->index }}][reference_code]" 
                                       placeholder="Reference Code" 
                                       class="w-32 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm dark:bg-gray-700 dark:text-gray-200"
                                       data-payment-id="{{ $payment->id }}">
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-end space-x-2">
                            <button type="button" onclick="toggleBulkConfirm()" 
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                                Confirm Selected
                            </button>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pickup Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($pendingPayments as $payment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 {{ $payment->type === 'group_full_payment' ? 'bg-blue-200 dark:bg-blue-700 border-2 border-blue-400 dark:border-blue-500' : 'bg-amber-200 dark:bg-amber-700 border-2 border-amber-400 dark:border-amber-500' }} rounded-full flex items-center justify-center mr-3">
                                            <span class="{{ $payment->type === 'group_full_payment' ? 'text-blue-900 dark:text-blue-100' : 'text-amber-900 dark:text-amber-100' }} font-bold text-sm">
                                                {{ $payment->type === 'group_full_payment' ? '👥' : '👤' }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $payment->user_phone }}
                                                @if($payment->type === 'group_full_payment' && $payment->group_size > 1)
                                                    <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 rounded-full">
                                                        Group ({{ $payment->group_size }} people)
                                                    </span>
                                                @endif
                                            </div>
                                            @if($payment->type === 'group_full_payment' && $payment->childPayments()->exists())
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Main booker • Paid for {{ $payment->group_size }} passengers
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full border-2 
                                        @if($payment->type === 'group_full_payment')
                                            bg-blue-200 text-blue-900 border-blue-400 dark:bg-blue-800 dark:text-blue-100 dark:border-blue-500
                                        @elseif($payment->type === 'deposit')
                                            bg-orange-200 text-orange-900 border-orange-400 dark:bg-orange-800 dark:text-orange-100 dark:border-orange-500
                                        @else
                                            bg-indigo-200 text-indigo-900 border-indigo-400 dark:bg-indigo-800 dark:text-indigo-100 dark:border-indigo-500
                                        @endif">
                                        @if($payment->type === 'group_full_payment')
                                            👥 GROUP
                                        @else
                                            {{ $payment->type === 'deposit' ? '💰 DEPOSIT' : '💳 REMAINING' }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            HK$ {{ number_format($payment->amount, 2) }}
                                        </span>
                                        @if($payment->type === 'group_full_payment' && $payment->group_size > 1)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                (HK$ {{ number_format($payment->amount / $payment->group_size, 2) }} per person)
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($payment->type === 'group_full_payment' && $payment->childPayments()->exists())
                                        <div class="space-y-1">
                                            @php $allGroupPayments = collect([$payment])->merge($payment->childPayments); @endphp
                                            @foreach($allGroupPayments->take(2) as $index => $groupPayment)
                                                @php
                                                    $groupTripJoin = $groupPayment->tripJoins->first();
                                                    $pickupLocation = $groupTripJoin ? $groupTripJoin->pickup_location : 'Not specified';
                                                @endphp
                                                <div class="text-xs text-gray-900 dark:text-gray-100">
                                                    <strong>{{ $index + 1 }}.</strong> {{ Str::limit($pickupLocation, 30) }}
                                                </div>
                                            @endforeach
                                            @if($allGroupPayments->count() > 2)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    +{{ $allGroupPayments->count() - 2 }} more locations...
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        @php
                                            $mainTripJoin = $payment->tripJoins->first();
                                        @endphp
                                        <span class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ ($mainTripJoin && $mainTripJoin->pickup_location) ? $mainTripJoin->pickup_location : 'Not specified' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $payment->created_at->format('Y-m-d H:i') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.payment-confirmation.show', $payment) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                        Confirm Payment
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Confirmed Payments -->
            @if($confirmedPayments->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        ✅ Confirmed Payments ({{ $confirmedPayments->count() }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reference Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Confirmed At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($confirmedPayments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-emerald-200 dark:bg-emerald-700 border-2 border-emerald-400 dark:border-emerald-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-emerald-900 dark:text-emerald-100 font-bold text-sm">
                                                {{-- {{ substr($payment->user->username, 0, 1) }} --}}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $payment->user_phone }}
                                            </div>
                                            {{-- <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $payment->user->email }}
                                            </div> --}}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full border-2 {{ $payment->type === 'deposit' ? 'bg-orange-200 text-orange-900 border-orange-400 dark:bg-orange-800 dark:text-orange-100 dark:border-orange-500' : 'bg-indigo-200 text-indigo-900 border-indigo-400 dark:bg-indigo-800 dark:text-indigo-100 dark:border-indigo-500' }}">
                                        {{ $payment->type === 'deposit' ? '💰 DEPOSIT' : '💳 REMAINING' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        HK$ {{ number_format($payment->amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-mono font-bold">
                                        {{ $payment->reference_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $payment->updated_at->format('Y-m-d H:i') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($pendingPayments->count() === 0 && $confirmedPayments->count() === 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="text-gray-400 dark:text-gray-500 text-lg mb-2">📭</div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No Payment Records</h3>
                    <p class="text-gray-600 dark:text-gray-400">There are no payment records for this trip yet.</p>
                </div>
            </div>
            @endif
    </div>

    <script>
        function toggleBulkConfirm() {
            const form = document.getElementById('bulk-confirm-form');
            form.classList.toggle('hidden');
        }

        // Auto-populate reference codes based on pattern
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.bulk-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const paymentId = this.value;
                    const referenceInput = document.querySelector(`input[data-payment-id="${paymentId}"]`);
                    
                    if (this.checked && !referenceInput.value) {
                        // Auto-generate reference code suggestion
                        const now = new Date();
                        const timestamp = now.toISOString().slice(2, 10).replace(/-/g, '');
                        referenceInput.value = `REF${paymentId}${timestamp}`;
                    }
                });
            });
        });
    </script>
    </div>
@endsection