@extends('admin.layout')

@section('title', 'Trip Management - Details')
@section('page-title', 'Trip Details')

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

        /* Mobile card styles */
        .mobile-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: none;
            width: 100%;
        }

        .mobile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 2px solid #e5e7eb;
        }

        .mobile-trip-id {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 18px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .mobile-route {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 12px;
        }

        .mobile-route-icon {
            color: #3b82f6;
            margin: 0 12px;
            font-size: 18px;
        }

        .mobile-location {
            font-weight: 600;
            color: #1f2937;
            font-size: 16px;
        }

        .mobile-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .mobile-info-item {
            text-align: center;
            padding: 12px;
            background: #f1f5f9;
            border-radius: 8px;
        }

        .mobile-info-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .mobile-info-value {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .mobile-status {
            flex-shrink: 0;
        }

        .mobile-status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .mobile-section {
            margin-bottom: 16px;
        }

        .mobile-section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }

        .mobile-participant {
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            border-left: 4px solid #3b82f6;
        }

        .mobile-participant-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .mobile-participant-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            font-size: 12px;
            color: #64748b;
        }

        .mobile-actions {
            display: flex;
            gap: 8px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .mobile-action-btn {
            flex: 1;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 100px;
            width: 100%;
            height: 48px;
            line-height: 1;
            white-space: nowrap;
            box-sizing: border-box;
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
            <!-- Mobile Trip Header -->
            <div class="mobile-card">
                <div class="mobile-header">
                    <div class="mobile-trip-id">Trip #{{ $trip->id }}</div>
                    <div class="mobile-status">
                        <span class="mobile-status-badge" style="
                            background-color: {{ $trip->trip_status === 'awaiting' ? '#dbeafe' : 
                               ($trip->trip_status === 'departed' ? '#f3e8ff' : 
                               ($trip->trip_status === 'charging' ? '#fef3c7' : 
                               ($trip->trip_status === 'completed' ? '#dcfce7' : '#fee2e2'))) }};
                            color: {{ $trip->trip_status === 'awaiting' ? '#1e40af' : 
                               ($trip->trip_status === 'departed' ? '#7c3aed' : 
                               ($trip->trip_status === 'charging' ? '#92400e' : 
                               ($trip->trip_status === 'completed' ? '#166534' : '#991b1b'))) }};
                        ">
                            {{ ucfirst($trip->trip_status) }}
                        </span>
                    </div>
                </div>

                <div class="mobile-route">
                    <i class="fas fa-map-marker-alt mobile-route-icon"></i>
                    <span class="mobile-location">{{ $trip->dropoff_location }}</span>
                </div>

                <div class="mobile-info-grid">
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Creator</div>
                        <div class="mobile-info-value">{{ $trip->creator->username ?? 'Unknown' }}</div>
                    </div>
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Departure</div>
                        <div class="mobile-info-value">{{ $trip->planned_departure_time ? $trip->planned_departure_time->format('m/d H:i') : 'TBD' }}</div>
                    </div>
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Type</div>
                        <div class="mobile-info-value">{{ ucfirst($trip->type) }}</div>
                    </div>
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Price</div>
                        <div class="mobile-info-value">HK$ {{ number_format($trip->price_per_person, 2) }}</div>
                    </div>
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Min/Max</div>
                        <div class="mobile-info-value">{{ $trip->min_passengers }}/{{ $trip->max_people }}</div>
                    </div>
                    <div class="mobile-info-item">
                        <div class="mobile-info-label">Discount</div>
                        <div class="mobile-info-value">HK$ {{ number_format($trip->four_person_discount, 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Mobile Participants -->
            <div class="mobile-card">
                <div class="mobile-section-title">
                    <i class="fas fa-users" style="margin-right: 8px; color: #3b82f6;"></i>
                    Participants ({{ optional($trip->joins)->count() ?? 0 }}/{{ $trip->max_people }})
                </div>
                
                @if(empty($trip->joins) || $trip->joins->isEmpty())
                    <div style="text-align: center; color: #64748b; padding: 20px;">
                        <i class="fas fa-user-slash" style="font-size: 24px; margin-bottom: 8px;"></i>
                        <p>No participants yet</p>
                    </div>
                @else
                    @foreach($trip->joins as $join)
                        <div class="mobile-participant">
                            <div class="mobile-participant-name">
                                {{ $join->user->username ?? $join->user->name ?? 'Deleted User' }}
                            </div>
                            <div class="mobile-participant-details">
                                <div><strong>Role:</strong> {{ ucfirst($join->join_role) }}</div>
                                <div><strong>Fee:</strong> ¥{{ number_format($join->user_fee, 2) }}</div>
                                <div><strong>Pickup:</strong> {{ $join->pickup_location ?? '-' }}</div>
                                <div><strong>Voted:</strong> 
                                    @if($join->hasVoted())
                                        <span style="color: #059669;">Yes</span>
                                    @else
                                        <span style="color: #dc2626;">No</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Mobile Payment Records -->
            @if(!empty($trip->payments) && !$trip->payments->isEmpty())
                <div class="mobile-card">
                    <div class="mobile-section-title">
                        <i class="fas fa-credit-card" style="margin-right: 8px; color: #3b82f6;"></i>
                        Payment Records
                    </div>
                    
                    @foreach($trip->payments as $payment)
                        <div class="mobile-participant">
                            <div class="mobile-participant-name">
                                {{ $payment->user->name ?? 'Deleted User' }}
                            </div>
                            <div class="mobile-participant-details">
                                <div><strong>Amount:</strong> ¥{{ number_format($payment->payment_amount, 2) }}</div>
                                <div><strong>Status:</strong> 
                                    <span style="color: {{ $payment->payment_status === 'paid' ? '#059669' : ($payment->payment_status === 'refunded' ? '#3b82f6' : '#eab308') }};">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </div>
                                <div style="grid-column: 1 / -1;"><strong>Time:</strong> {{ $payment->payment_time ? $payment->payment_time->format('Y-m-d H:i') : 'Not Paid' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Mobile Actions -->
            <div class="mobile-card">
                <h3 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px;">
                    Actions
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <!-- Edit and Back buttons in same row -->
                    <div style="display: flex; gap: 12px;">
                        <a href="{{ route('admin.trips.edit', $trip->id) }}" class="mobile-action-btn mobile-btn-blue" style="flex: 1;">
                            <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                            </svg>
                            Edit Trip
                        </a>
                        
                        <a href="{{ route('admin.trips.index') }}" class="mobile-action-btn mobile-btn-gray" style="flex: 1;">
                            <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Back to List
                        </a>
                    </div>
                    
                    <!-- Payment Confirmation Button -->
                    <a href="{{ route('admin.payment-confirmation.index', $trip->id) }}" class="mobile-action-btn" style="background: #059669 !important; color: white !important;">
                        <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                        Payment Confirmation
                    </a>
                    
                    <!-- Delete Button -->
                    <button onclick="showMobileDeleteModal({{ $trip->id }})" class="mobile-action-btn" style="background: #ef4444 !important; color: white !important;">
                        <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Delete Trip
                    </button>
                </div>
            </div>
        </div>
    @else
        <!-- Desktop Layout -->
        <div class="desktop-only">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Trip Details #{{ $trip->id }}</h2>
                <a href="{{ route('admin.trips.index') }}" class="text-blue-600 hover:text-blue-900 text-sm">
                    ← Back to Trip List
                </a>
            </div>

            <!-- Trip Basic Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 py-2">
                    <div class="py-3">
                        <p class="text-sm text-gray-500">Creator</p>
                        <p class="text-gray-900">{{ $trip->creator->username ?? 'Unknown User' }}</p>
                    </div>
                    <div class="py-3">
                        <p class="text-sm text-gray-500">Destination</p>
                        <p class="text-gray-900">{{ $trip->dropoff_location }}</p>
                    </div>
                    <div class="py-3">
                        <p class="text-sm text-gray-500">Planned Departure</p>
                        <p class="text-gray-900">{{ $trip->planned_departure_time ? $trip->planned_departure_time->format('Y-m-d H:i') : 'To be determined' }}</p>
                    </div>
                    <div class="py-3">
                        <p class="text-sm text-gray-500">Trip Type</p>
                        <p class="text-gray-900">{{ ucfirst($trip->type) }}</p>
                    </div>
                    <div class="py-3">
                        <p class="text-sm text-gray-500">Price Per Person</p>
                        <p class="text-gray-900">HK$ {{ number_format($trip->price_per_person, 2) }}</p>
                    </div>
                    <div class="py-3">
                        <p class="text-sm text-gray-500">Min Passengers</p>
                        <p class="text-gray-900">{{ $trip->min_passengers }} people</p>
                    </div>
                    <div class="py-3">
                        <p class="text-sm text-gray-500">Max Capacity</p>
                        <p class="text-gray-900">{{ $trip->max_people }} people</p>
                    </div>
                    <div class="py-3">
                        <p class="text-sm text-gray-500">4-Person Discount</p>
                        <p class="text-gray-900">HK$ {{ number_format($trip->four_person_discount, 2) }}</p>
                    </div>
                    <div class="py-3">
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $trip->trip_status === 'awaiting' ? 'bg-blue-100 text-blue-800' : 
                               ($trip->trip_status === 'departed' ? 'bg-purple-100 text-purple-800' : 
                               ($trip->trip_status === 'charging' ? 'bg-yellow-100 text-yellow-800' :
                               ($trip->trip_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))) }}">
                            {{ ucfirst($trip->trip_status) }}
                        </span>
                    </div>
                    <div class="py-3">
                        <p class="text-sm text-gray-500">Invitation Code</p>
                        <p class="text-gray-900 font-mono">{{ $trip->invitation_code }}</p>
                    </div>
                    <div class="py-3">
                        <p class="text-sm text-gray-500">Created At</p>
                        <p class="text-gray-900">{{ $trip->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Participants List -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    Participants ({{ optional($trip->joins)->count() ?? 0 }}/{{ $trip->max_people }})
                </h3>
                @if(empty($trip->joins) || $trip->joins->isEmpty())
                    <p class="text-gray-500">No participants yet</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pickup Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voted?</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee (¥)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($trip->joins as $join)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $join->user->username ?? $join->user->name ?? 'Deleted User' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ucfirst($join->join_role) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $join->pickup_location ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($join->hasVoted())
                                                <span class="text-green-600">Yes</span>
                                            @else
                                                <span class="text-red-600">No</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($join->user_fee, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Payment Records -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-lg font-semibold text-gray-800">Payment Records</h3>
                    <a href="{{ route('admin.payment-confirmation.index', $trip->id) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                        💰 Manage Payment Confirmations
                    </a>
                </div>
                @if(empty($trip->payments) || $trip->payments->isEmpty())
                    <p class="text-gray-500">No payment records yet</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (¥)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($trip->payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $payment->user->name ?? 'Deleted User' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($payment->payment_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $payment->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                                   ($payment->payment_status === 'refunded' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($payment->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $payment->payment_time ? $payment->payment_time->format('Y-m-d H:i') : 'Not Paid' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Mobile Delete Confirmation Modal -->
    @if($isMobile)
        <div id="mobileDeleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 12px; max-width: 350px; width: 90%; margin: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3); max-height: 80vh; overflow-y: auto;">
                <!-- Modal Header -->
                <div style="padding: 20px; text-align: center;">
                    <div style="width: 60px; height: 60px; background-color: #fee2e2; border-radius: 50%; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 30px; height: 30px; color: #dc2626;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 8px;">Confirm Delete</h3>
                    <p style="color: #6b7280; margin-bottom: 4px;">Are you sure you want to delete this trip?</p>
                    <p style="color: #6b7280; font-size: 14px;">Trip ID: <span id="mobileTripsInfo" style="font-weight: 600;">#{{ $trip->id }}</span></p>
                </div>
                
                <!-- Modal Footer -->
                <div style="padding: 0 20px 20px 20px; display: flex; gap: 12px;">
                    <button onclick="closeMobileDeleteModal()" style="flex: 1; padding: 12px 20px; background-color: #f3f4f6; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; font-size: 16px;">
                        Cancel
                    </button>
                    <button onclick="confirmMobileDeleteSubmit()" style="flex: 1; padding: 12px 20px; background-color: #dc2626; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; font-size: 16px;">
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Hidden Delete Form for Mobile -->
        <form id="mobileDeleteForm" action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <!-- Mobile JavaScript -->
        <script>
            function showMobileDeleteModal(tripId) {
                document.getElementById('mobileDeleteModal').style.display = 'flex';
            }

            function closeMobileDeleteModal() {
                document.getElementById('mobileDeleteModal').style.display = 'none';
            }

            function confirmMobileDeleteSubmit() {
                document.getElementById('mobileDeleteForm').submit();
            }

            // Touch feedback for mobile buttons
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.mobile-action-btn').forEach(btn => {
                    btn.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.95)';
                        this.style.opacity = '0.8';
                    });
                    
                    btn.addEventListener('touchend', function() {
                        this.style.transform = 'scale(1)';
                        this.style.opacity = '1';
                    });
                });
            });

            // Close modal when clicking outside
            document.addEventListener('click', function(event) {
                const modal = document.getElementById('mobileDeleteModal');
                if (event.target === modal) {
                    closeMobileDeleteModal();
                }
            });
        </script>
    @endif
@endsection