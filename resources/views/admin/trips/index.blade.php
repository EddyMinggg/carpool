@extends('admin.layout')

@section('title', 'Trip Management - List')
@section('page-title', 'Trip Management')

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
        .mobile-trip-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: none;
            width: 100%;
            position: relative;
            text-decoration: none;
            color: inherit;
            display: block;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .mobile-trip-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: inherit;
        }

        .mobile-trip-card:active {
            transform: translateY(0px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .mobile-trip-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .mobile-trip-id {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            min-width: 60px;
            text-align: center;
            flex-shrink: 0;
        }

        .mobile-trip-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            flex-shrink: 0;
            text-align: center;
            min-width: 80px;
        }

        .mobile-trip-content {
            margin-top: 8px;
        }

        .mobile-route {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            padding: 8px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .mobile-route-icon {
            color: #3b82f6;
            margin: 0 8px;
            font-size: 16px;
        }

        .mobile-location {
            font-weight: 600;
            color: #1f2937;
        }

        .mobile-trip-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin: 12px 0;
        }

        .mobile-info-item {
            text-align: center;
            padding: 8px;
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

        .mobile-creator {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: #eff6ff;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .mobile-creator-icon {
            color: #3b82f6;
            margin-right: 8px;
        }

        .mobile-search-container {
            margin-bottom: 16px;
            padding: 12px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .mobile-search-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            background: white;
        }

        .mobile-search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .mobile-create-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border-radius: 50%;
            border: none;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            cursor: pointer;
            z-index: 1000;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .mobile-create-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5);
            color: white;
        }

        .mobile-create-btn:active {
            transform: scale(0.95);
        }

        .mobile-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        .mobile-stat-card {
            background: white;
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .mobile-stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .mobile-stat-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
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

        /* Mobile Status Filter Styles */
        .status-filter-btn {
            transition: all 0.2s ease;
        }

        .status-filter-btn:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }

        .status-filter-btn:active {
            transform: translateY(0px);
        }

        .status-filter-btn.active {
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3) !important;
        }

        /* 統一的 DataTables 按鈕樣式 */
        .dt-buttons {
            margin-bottom: 1rem;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .dt-button {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 10px 18px !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            position: relative !important;
            overflow: hidden !important;
            min-width: 100px !important;
            justify-content: center !important;
            cursor: pointer !important;
        }
        
        .dt-button:before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: -100% !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent) !important;
            transition: left 0.5s !important;
        }
        
        .dt-button:hover:before {
            left: 100% !important;
        }
        
        .dt-button:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
            border-color: #3b82f6 !important;
        }
        
        .dt-button:nth-child(1) i { color: #8b5cf6 !important; }
        .dt-button:nth-child(2) i { color: #10b981 !important; }
        .dt-button:nth-child(3) i { color: #059669 !important; }
        .dt-button:nth-child(4) i { color: #ef4444 !important; }
        .dt-button:nth-child(5) i { color: #6366f1 !important; }
        .dt-button:hover i { color: white !important; }

        /* DataTable 間距調整 */
        .dataTables_length {
            margin-top: 1.5rem !important;
            margin-bottom: 1rem !important;
        }
        
        .dataTables_info {
            padding-top: 1.5rem !important;
            margin-bottom: 0.5rem !important;
        }
        
        .dataTables_paginate {
            padding-top: 1rem !important;
        }

        /* Action 按鈕樣式 */
        .action-btn {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            margin: 0 0.125rem;
            border-radius: 0.25rem;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .action-btn-blue {
            background-color: #3b82f6;
            color: white;
        }
        
        .action-btn-blue:hover {
            background-color: #2563eb;
            color: white;
        }
        
        .action-btn-yellow {
            background-color: #eab308;
            color: white;
        }
        
        .action-btn-yellow:hover {
            background-color: #ca8a04;
            color: white;
        }
        
        .action-btn-red {
            background-color: #ef4444;
            color: white;
        }
        
        .action-btn-red:hover {
            background-color: #dc2626;
            color: white;
        }
    </style>

    @if($isMobile)
        <!-- Mobile Layout -->
        <div class="mobile-only" style="padding: 12px; background-color: #f1f5f9; min-height: 100vh;">
            <!-- Mobile Statistics -->
            <div class="mobile-stats">
                <div class="mobile-stat-card">
                    <div class="mobile-stat-number">{{ $trips->total() }}</div>
                    <div class="mobile-stat-label">Total Trips</div>
                </div>
                <div class="mobile-stat-card">
                    <div class="mobile-stat-number">{{ $trips->where('trip_status', 'awaiting')->count() }}</div>
                    <div class="mobile-stat-label">awaiting</div>
                </div>
            </div>

            <!-- Mobile Search -->
            <div class="mobile-search-container">
                <input type="text" id="mobileSearch" class="mobile-search-input" placeholder="Search trips...">
            </div>

            <!-- Mobile Status Filter -->
            <div class="mobile-filter-container" style="
                margin-bottom: 16px;
                padding: 12px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            ">
                <div style="margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #374151;">Filter by Status:</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <!-- First Row -->
                    <button onclick="filterByStatus('all')" class="status-filter-btn active" data-status="all" style="
                        padding: 10px 12px;
                        border: 2px solid #3b82f6;
                        background: #3b82f6;
                        color: white;
                        border-radius: 8px;
                        font-size: 12px;
                        font-weight: 600;
                        text-transform: uppercase;
                        cursor: pointer;
                        transition: all 0.2s;
                    ">All Trips</button>
                    
                    <button onclick="filterByStatus('awaiting')" class="status-filter-btn" data-status="awaiting" style="
                        padding: 10px 12px;
                        border: 2px solid #dbeafe;
                        background: #dbeafe;
                        color: #1e40af;
                        border-radius: 8px;
                        font-size: 12px;
                        font-weight: 600;
                        text-transform: uppercase;
                        cursor: pointer;
                        transition: all 0.2s;
                    ">Awaiting</button>

                    <!-- Second Row -->
                    <button onclick="filterByStatus('charging')" class="status-filter-btn" data-status="charging" style="
                        padding: 10px 12px;
                        border: 2px solid #fed7aa;
                        background: #fed7aa;
                        color: #c2410c;
                        border-radius: 8px;
                        font-size: 12px;
                        font-weight: 600;
                        text-transform: uppercase;
                        cursor: pointer;
                        transition: all 0.2s;
                    ">Charging</button>
                    
                    <button onclick="filterByStatus('completed')" class="status-filter-btn" data-status="completed" style="
                        padding: 10px 12px;
                        border: 2px solid #dcfce7;
                        background: #dcfce7;
                        color: #166534;
                        border-radius: 8px;
                        font-size: 12px;
                        font-weight: 600;
                        text-transform: uppercase;
                        cursor: pointer;
                        transition: all 0.2s;
                    ">Completed</button>
                </div>
            </div>

            <!-- Mobile Trip Cards -->
            <div id="mobileTripsContainer">
                @foreach($trips as $trip)
                    <a href="{{ route('admin.trips.show', $trip->id) }}" class="mobile-trip-card" data-search="{{ strtolower($trip->id . ' ' . ($trip->creator->username ?? 'Unknown') . ' ' . $trip->dropoff_location . ' ' . $trip->trip_status) }}" data-status="{{ $trip->trip_status }}" style="text-decoration: none; color: inherit; display: block; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                        <div class="mobile-trip-header">
                            <div class="mobile-trip-id">#{{ $trip->id }}</div>
                            <div class="mobile-trip-status" style="
                                background-color: {{ $trip->trip_status === 'awaiting' ? '#dbeafe' : 
                                   ($trip->trip_status === 'charging' ? '#fed7aa' : 
                                   ($trip->trip_status === 'completed' ? '#dcfce7' : '#fee2e2')) }};
                                color: {{ $trip->trip_status === 'awaiting' ? '#1e40af' : 
                                   ($trip->trip_status === 'charging' ? '#c2410c' : 
                                   ($trip->trip_status === 'completed' ? '#166534' : '#991b1b')) }};
                            ">
                                {{ ucfirst($trip->trip_status) }}
                            </div>
                        </div>

                        <div class="mobile-creator">
                            <i class="fas fa-user mobile-creator-icon"></i>
                            <span style="font-weight: 600;">{{ $trip->creator->username ?? 'Unknown User' }}</span>
                        </div>

                        <div class="mobile-route">
                            <i class="fas fa-map-marker-alt mobile-route-icon"></i>
                            <span class="mobile-location">{{ $trip->dropoff_location }}</span>
                        </div>

                        <!-- Time Slot Badge -->
                        <div style="margin-bottom: 12px;">
                            <span style="
                                background-color: {{ $trip->isGoldenHour() ? '#fef3c7' : '#e0e7ff' }};
                                color: {{ $trip->isGoldenHour() ? '#92400e' : '#3730a3' }};
                                padding: 6px 12px;
                                border-radius: 20px;
                                font-size: 12px;
                                font-weight: 600;
                                text-transform: uppercase;
                            ">
                                {{ $trip->isGoldenHour() ? '🌟 Golden Hour' : '⏰ Regular Hour' }}
                            </span>
                        </div>

                        <div class="mobile-trip-info">
                            <div class="mobile-info-item">
                                <div class="mobile-info-label">Departure</div>
                                <div class="mobile-info-value">{{ $trip->planned_departure_time->format('m/d H:i') }}</div>
                            </div>
                            <div class="mobile-info-item">
                                <div class="mobile-info-label">Capacity</div>
                                <div class="mobile-info-value">{{ $trip->max_people }} people</div>
                            </div>
                            <div class="mobile-info-item">
                                <div class="mobile-info-label">Price</div>
                                <div class="mobile-info-value">HK${{ $trip->price_per_person }}/person</div>
                            </div>
                            <div class="mobile-info-item">
                                <div class="mobile-info-label">Min Passengers</div>
                                <div class="mobile-info-value">{{ $trip->min_passengers }} person{{ $trip->min_passengers > 1 ? 's' : '' }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Mobile Pagination -->
            <div style="margin-top: 30px; padding-bottom: 100px;">
                {{ $trips->links('pagination::mobile') }}
            </div>

            <!-- Mobile Create Button -->
            <a href="{{ route('admin.trips.create') }}" class="mobile-create-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    @else
        <!-- Desktop Layout -->
        <div class="desktop-only">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800">Trip Management</h2>
                    <div class="flex space-x-3">
                        @php
                            $pendingCount = \App\Models\Trip::where('trip_status', \App\Models\Trip::STATUS_AWAITING)
                                ->where('type', \App\Models\Trip::TYPE_NORMAL)
                                ->get()
                                ->filter(function ($trip) {
                                    $deadline = \Carbon\Carbon::parse($trip->planned_departure_time)->subHours(48);
                                    if (!\Carbon\Carbon::now()->greaterThanOrEqualTo($deadline)) {
                                        return false;
                                    }
                                    $confirmedCount = $trip->activeJoins()->where('payment_confirmed', 1)->count();
                                    return $confirmedCount === 2;
                                })->count();
                        @endphp
                        <a href="{{ route('admin.trips.pending-review') }}" 
                            class="relative bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Pending Review
                            @if($pendingCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('admin.trips.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Create New Trip
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <table id="tripsTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Trip ID</th>
                                <th>Creator</th>
                                <th>Destination</th>
                                <th>Departure Time</th>
                                <th>Time Slot</th>
                                <th>Price/Min</th>
                                <th>Max People</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trips as $trip)
                                <tr>
                                    <td>{{ $trip->id }}</td>
                                    <td>{{ $trip->creator->username ?? 'Unknown User' }}</td>
                                    <td>{{ $trip->dropoff_location }}</td>
                                    <td>{{ $trip->planned_departure_time->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $trip->isGoldenHour() ? 'bg-yellow-100 text-yellow-800' : 'bg-indigo-100 text-indigo-800' }}">
                                            {{ $trip->isGoldenHour() ? '🌟 Golden' : '⏰ Regular' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-sm">
                                            <div class="font-medium">HK${{ $trip->price_per_person }}/person</div>
                                            <div class="text-gray-500">Min: {{ $trip->min_passengers }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $trip->max_people }}</td>
                                    <td>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $trip->trip_status === 'awaiting' ? 'bg-blue-100 text-blue-800' : 
                                               ($trip->trip_status === 'charging' ? 'bg-orange-100 text-orange-800' : 
                                               ($trip->trip_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                            {{ ucfirst($trip->trip_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex space-x-1">
                                            <a href="{{ route('admin.trips.show', $trip->id) }}" 
                                               class="action-btn action-btn-blue"
                                               title="View Trip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.trips.edit', $trip->id) }}" 
                                               class="action-btn action-btn-yellow"
                                               title="Edit Trip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="showDeleteModal({{ $trip->id }}, '{{ $trip->dropoff_location }}', {{ $trip->activeJoins->count() }})" 
                                                    class="action-btn action-btn-red"
                                                    title="Delete Trip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $trip->id }}" action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div
            style="background: white; border-radius: 8px; max-width: 400px; width: 90%; margin: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3); max-height: 80vh; overflow-y: auto;">
            <!-- Modal Header -->
            <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #dc2626;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                    Confirm Delete Trip
                </h3>
            </div>

            <!-- Modal Body -->
            <div style="padding: 20px;">
                <div id="canDeleteContent">
                    <p style="margin: 0 0 16px 0; color: #374151; line-height: 1.5; font-size: 16px;">
                        Are you sure you want to delete this trip?
                    </p>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px; margin-bottom: 16px;">
                        <p style="margin: 0; font-size: 14px; color: #dc2626;">
                            <i class="fas fa-info-circle" style="margin-right: 6px;"></i>
                            <strong>Trip ID:</strong> <span id="tripId"></span>
                        </p>
                        <p style="margin: 8px 0 0 0; font-size: 14px; color: #dc2626;">
                            <i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i>
                            <strong>Destination:</strong> <span id="tripDestination"></span>
                        </p>
                    </div>
                </div>

                <div id="cannotDeleteContent" style="display: none;">
                    <p style="margin: 0 0 16px 0; color: #374151; line-height: 1.5; font-size: 16px;">
                        This trip cannot be deleted because there are active bookings.
                    </p>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px; margin-bottom: 16px;">
                        <p style="margin: 0; font-size: 14px; color: #dc2626;">
                            <i class="fas fa-users" style="margin-right: 6px;"></i>
                            <strong><span id="bookingsCount"></span> booking(s)</strong> for this trip
                        </p>
                        <p style="margin: 8px 0 0 0; font-size: 13px; color: #6b7280;">
                            Please cancel all bookings before deleting this trip.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div style="padding: 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; gap: 12px;">
                <button onclick="closeDeleteModal()"
                    style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px; transition: background-color 0.2s; flex: 1;"
                    onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    <span id="cancelButtonText">Cancel</span>
                </button>
                <button id="confirmDeleteButton" onclick="confirmDeleteSubmit()"
                    style="padding: 12px 24px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px; transition: background-color 0.2s; flex: 1;"
                    onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                    <i class="fas fa-trash" style="margin-right: 6px;"></i>
                    Delete Trip
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile-specific modal styles --}}
    <style>
        @media (max-width: 640px) {
            #deleteModal>div {
                width: 95% !important;
                margin: 10px !important;
                max-height: 90vh !important;
            }

            #deleteModal button {
                width: 100% !important;
                min-height: 44px !important;
            }
        }

        /* Modal animation */
        #deleteModal {
            animation: fadeIn 0.15s ease-out;
        }

        #deleteModal>div {
            animation: slideUp 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@push('scripts')
<script>
const isMobile = {{ $isMobile ? 'true' : 'false' }};

if (isMobile) {
    // Mobile search and filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('mobileSearch');
        const tripCards = document.querySelectorAll('.mobile-trip-card');
        let currentStatusFilter = 'all';

        // Search functionality
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                applyFilters();
            });
        }

        // Apply both search and status filters
        function applyFilters() {
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            
            tripCards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                const cardStatus = card.getAttribute('data-status');
                const statusMatch = currentStatusFilter === 'all' || cardStatus === currentStatusFilter;
                const searchMatch = searchTerm === '' || searchData.includes(searchTerm);
                
                if (statusMatch && searchMatch) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Status filter function
        window.filterByStatus = function(status) {
            currentStatusFilter = status;
            
            // Update button styles
            document.querySelectorAll('.status-filter-btn').forEach(btn => {
                const btnStatus = btn.getAttribute('data-status');
                if (btnStatus === status) {
                    btn.classList.add('active');
                    // Active button styles
                    if (status === 'all') {
                        btn.style.background = '#3b82f6';
                        btn.style.borderColor = '#3b82f6';
                        btn.style.color = 'white';
                    } else if (status === 'awaiting') {
                        btn.style.background = '#1e40af';
                        btn.style.borderColor = '#1e40af';
                        btn.style.color = 'white';
                    } else if (status === 'charging') {
                        btn.style.background = '#c2410c';
                        btn.style.borderColor = '#c2410c';
                        btn.style.color = 'white';
                    } else if (status === 'completed') {
                        btn.style.background = '#166534';
                        btn.style.borderColor = '#166534';
                        btn.style.color = 'white';
                    }
                } else {
                    btn.classList.remove('active');
                    // Inactive button styles
                    const btnStatus = btn.getAttribute('data-status');
                    if (btnStatus === 'all') {
                        btn.style.background = '#f3f4f6';
                        btn.style.borderColor = '#d1d5db';
                        btn.style.color = '#374151';
                    } else if (btnStatus === 'awaiting') {
                        btn.style.background = '#dbeafe';
                        btn.style.borderColor = '#dbeafe';
                        btn.style.color = '#1e40af';
                    } else if (btnStatus === 'charging') {
                        btn.style.background = '#fed7aa';
                        btn.style.borderColor = '#fed7aa';
                        btn.style.color = '#c2410c';
                    } else if (btnStatus === 'completed') {
                        btn.style.background = '#dcfce7';
                        btn.style.borderColor = '#dcfce7';
                        btn.style.color = '#166534';
                    }
                }
            });
            
            applyFilters();
        }

        // Touch feedback for mobile trip cards and create button
        document.querySelectorAll('.mobile-trip-card').forEach(card => {
            card.addEventListener('touchstart', function() {
                this.style.transform = 'translateY(0px) scale(0.98)';
                this.style.opacity = '0.8';
            });
            
            card.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.style.transform = 'translateY(-2px) scale(1)';
                    this.style.opacity = '1';
                }, 100);
            });
        });

        document.querySelectorAll('.mobile-create-btn').forEach(btn => {
            btn.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.95)';
            });
            
            btn.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 100);
            });
        });
    });
} else {
    // Desktop DataTable
    $(document).ready(function() {
        $('#tripsTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[3, 'desc']], // 按出發時間排序（仍然是第3欄）
            columnDefs: [
                {
                    targets: [8], // Actions column（現在是第8欄）
                    orderable: false,
                    searchable: false
                }
            ],
            dom: 'Bfrtlip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                    className: 'dt-button',
                    titleAttr: 'Copy table data to clipboard'
                },
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    className: 'dt-button',
                    titleAttr: 'Export to CSV format'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'dt-button',
                    titleAttr: 'Export to Excel format'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'dt-button',
                    titleAttr: 'Export to PDF format'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'dt-button',
                    titleAttr: 'Print table'
                }
            ],
            language: {
                search: "Search trips:",
                lengthMenu: "Show _MENU_ trips per page",
                info: "Showing _START_ to _END_ of _TOTAL_ trips",
                infoEmpty: "No trips found",
                infoFiltered: "(filtered from _MAX_ total trips)",
                emptyTable: "No trip data available",
                zeroRecords: "No trips match your search criteria",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    });

}

// Modal functions (both mobile and desktop)
let currentTripId = null;

function showDeleteModal(tripId, destination, bookingsCount) {
    currentTripId = tripId;
    
    // Set trip info
    document.getElementById('tripId').textContent = '#' + tripId;
    document.getElementById('tripDestination').textContent = destination;
    document.getElementById('bookingsCount').textContent = bookingsCount;
    
    // Show/hide content based on bookings
    const canDelete = bookingsCount === 0;
    document.getElementById('canDeleteContent').style.display = canDelete ? 'block' : 'none';
    document.getElementById('cannotDeleteContent').style.display = canDelete ? 'none' : 'block';
    document.getElementById('confirmDeleteButton').style.display = canDelete ? 'block' : 'none';
    document.getElementById('cancelButtonText').textContent = canDelete ? 'Cancel' : 'Close';
    
    // Show modal
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
    currentTripId = null;
}

function confirmDeleteSubmit() {
    if (currentTripId) {
        document.getElementById('delete-form-' + currentTripId).submit();
    }
}

// Close modal when clicking outside or pressing Escape
document.addEventListener('click', function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeDeleteModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && document.getElementById('deleteModal').style.display === 'flex') {
        closeDeleteModal();
    }
});
</script>
@endpush
