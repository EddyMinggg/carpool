@extends('admin.layout')

@section('title', 'Trips Pending Review - Admin Panel')
@section('page-title', 'Trips Pending Admin Review')

@section('content')
    <style>
        [x-cloak] {
            display: none !important;
        }

        .pending-card {
            background: #1f2937;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
            overflow: hidden;
            border: 2px solid #374151;
        }

        .pending-card-header {
            background: #111827;
            color: #e5e7eb;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #374151;
        }

        .pending-card-body {
            padding: 20px;
            background: #1f2937;
        }

        .passenger-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
            margin: 16px 0;
        }

        .passenger-card {
            background: #374151;
            border: 1px solid #4b5563;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            align-items: center;
        }

        .passenger-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 600;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .action-section {
            border-top: 1px solid #374151;
            padding-top: 20px;
            margin-top: 20px;
        }

        .btn-confirm {
            background: #10b981;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-confirm:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .btn-cancel {
            background: #ef4444;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .alert-warning {
            background: #374151;
            border: 1px solid #4b5563;
            border-left: 4px solid #f59e0b;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: start;
        }

        .alert-icon {
            color: #fbbf24;
            font-size: 24px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #1f2937;
            border-radius: 8px;
        }

        .empty-icon {
            font-size: 64px;
            color: #4b5563;
            margin-bottom: 16px;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-warning {
            background: #374151;
            color: #fbbf24;
        }

        .badge-danger {
            background: #7f1d1d;
            color: #fca5a5;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .action-buttons button {
            flex: 1;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.75);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }

        .modal-content {
            background: #1f2937;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            border: 1px solid #374151;
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #374151;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #374151;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-modal-cancel {
            background: #4b5563;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-modal-cancel:hover {
            background: #6b7280;
        }

        .btn-modal-confirm {
            background: #ef4444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-modal-confirm:hover {
            background: #dc2626;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                    Trips Pending Admin Review
                </h2>
                <a href="{{ route('admin.trips.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to All Trips
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <i class="fas fa-times-circle mr-2"></i>
                <span class="inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Warning Alert -->
        <div class="alert-warning">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h4 style="font-weight: 600; margin-bottom: 8px; color: #fbbf24; font-size: 16px;">
                    2-Passenger Trips Requiring Action
                </h4>
                <p style="color: #d1d5db; line-height: 1.6; margin: 0; font-size: 14px;">
                    These <strong>normal trips</strong> have passed the <strong>48-hour deadline</strong> with only <strong>2 confirmed passengers</strong>.<br>
                    Please choose: <strong>Confirm Departure</strong> or <strong>Cancel Trip</strong>
                </p>
            </div>
        </div>

        @if ($pendingTrips->isEmpty())
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 600; color: #d1d5db; margin-bottom: 8px;">
                    No Trips Pending Review
                </h3>
                <p style="color: #9ca3af; font-size: 14px;">
                    All trips are confirmed or have sufficient passengers.
                </p>
            </div>
        @else
            <!-- Pending Trips Cards -->
            @foreach ($pendingTrips as $trip)
                <div class="pending-card">
                    <!-- Card Header -->
                    <div class="pending-card-header">
                        <div>
                            <h3 style="font-size: 20px; font-weight: 700; margin: 0;">
                                <i class="fas fa-car mr-2"></i>Trip #{{ $trip->id }}
                            </h3>
                            <div style="margin-top: 4px; font-size: 14px; opacity: 0.9;">
                                <span class="badge badge-warning" style="background: rgba(255,255,255,0.3); color: white;">
                                    {{ strtoupper($trip->type) }}
                                </span>
                                <span style="margin-left: 8px;">
                                    <i class="fas fa-ticket-alt mr-1"></i>{{ $trip->invitation_code }}
                                </span>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 12px; opacity: 0.9; margin-bottom: 4px;">Departure Time</div>
                            <div style="font-size: 16px; font-weight: 700;">
                                {{ $trip->planned_departure_time->format('Y-m-d H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="pending-card-body">
                        <!-- Passengers Section -->
                        <div>
                            <h4 style="font-weight: 600; color: #d1d5db; margin-bottom: 12px; font-size: 14px;">
                                <i class="fas fa-users mr-2"></i>Confirmed Passengers (2):
                            </h4>
                            <div class="passenger-grid">
                                @foreach ($trip->activeJoins->where('payment_confirmed', 1) as $join)
                                    <div class="passenger-card">
                                        <div class="passenger-avatar">
                                            {{ strtoupper(substr($join->user->username ?? $join->user_phone, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: #e5e7eb; margin-bottom: 4px;">
                                                {{ $join->user->username ?? 'Guest' }}
                                            </div>
                                            <div style="font-size: 13px; color: #9ca3af; margin-bottom: 4px;">
                                                <i class="fas fa-phone mr-1"></i>{{ $join->user_phone }}
                                            </div>
                                            <div style="font-size: 13px; color: #10b981; font-weight: 600;">
                                                <i class="fas fa-dollar-sign mr-1"></i>Current Fee: ${{ number_format($join->user_fee, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Section -->
                        <div class="action-section" x-data="{ showCancelModal: false, tripId: {{ $trip->id }} }">
                            <div class="action-buttons">
                                <button type="button" 
                                        @click="showCancelModal = true"
                                        class="btn-cancel"
                                        style="flex: none; width: 100%;">
                                    <i class="fas fa-times mr-2"></i>Cancel Trip
                                </button>
                            </div>

                            <!-- Cancel Confirmation Modal -->
                            <div x-show="showCancelModal" 
                                 x-cloak
                                 @click.self="showCancelModal = false"
                                 class="modal-overlay">
                                <div class="modal-content" @click.away="showCancelModal = false">
                                    <div class="modal-header">
                                        <h3 style="font-size: 20px; font-weight: 700; color: #ef4444; margin: 0;">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>Cancel Trip #{{ $trip->id }}
                                        </h3>
                                    </div>
                                    <div class="modal-body">
                                        <p style="color: #d1d5db; line-height: 1.6; margin-bottom: 16px;">
                                            Are you sure you want to cancel this trip?
                                        </p>
                                        <div style="background: #374151; padding: 16px; border-radius: 8px; border-left: 4px solid #ef4444;">
                                            <p style="color: #fca5a5; font-weight: 600; margin-bottom: 8px;">
                                                <i class="fas fa-info-circle mr-2"></i>This action will:
                                            </p>
                                            <ul style="color: #d1d5db; margin-left: 20px; line-height: 1.8;">
                                                <li>Set trip status to <strong>cancelled</strong></li>
                                                <li>Notify both passengers</li>
                                                <li>Process offline refunds within 48 hours</li>
                                            </ul>
                                        </div>
                                        <p style="color: #9ca3af; margin-top: 16px; font-size: 14px;">
                                            <i class="fas fa-exclamation-circle mr-1"></i>This action cannot be undone.
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" 
                                                @click="showCancelModal = false"
                                                class="btn-modal-cancel">
                                            <i class="fas fa-arrow-left mr-2"></i>Go Back
                                        </button>
                                        <form action="{{ route('admin.trips.confirm-surcharge', $trip) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="surcharge_amount" value="0">
                                            <button type="submit" 
                                                    name="action" 
                                                    value="cancel"
                                                    class="btn-modal-confirm">
                                                <i class="fas fa-times-circle mr-2"></i>Confirm Cancellation
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- View Details Link -->
                        <div style="margin-top: 16px; text-align: center; padding-top: 16px; border-top: 1px solid #374151;">
                            <a href="{{ route('admin.trips.show', $trip) }}" 
                               style="color: #60a5fa; font-weight: 600; text-decoration: none; font-size: 14px;">
                                <i class="fas fa-eye mr-1"></i>View Full Details →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
