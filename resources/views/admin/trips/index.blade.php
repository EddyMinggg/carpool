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
                    <div class="mobile-stat-number">{{ $trips->where('trip_status', 'pending')->count() }}</div>
                    <div class="mobile-stat-label">Pending</div>
                </div>
            </div>

            <!-- Mobile Search -->
            <div class="mobile-search-container">
                <input type="text" id="mobileSearch" class="mobile-search-input" placeholder="Search trips...">
            </div>

            <!-- Mobile Trip Cards -->
            <div id="mobileTripsContainer">
                @foreach($trips as $trip)
                    <a href="{{ route('admin.trips.show', $trip->id) }}" class="mobile-trip-card" data-search="{{ strtolower($trip->id . ' ' . ($trip->creator->username ?? 'Unknown') . ' ' . $trip->pickup_location . ' ' . $trip->dropoff_location . ' ' . $trip->trip_status) }}" style="text-decoration: none; color: inherit; display: block; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                        <div class="mobile-trip-header">
                            <div class="mobile-trip-id">#{{ $trip->id }}</div>
                            <div class="mobile-trip-status" style="
                                background-color: {{ $trip->trip_status === 'pending' ? '#dbeafe' : 
                                   ($trip->trip_status === 'voting' ? '#fef3c7' : 
                                   ($trip->trip_status === 'completed' ? '#dcfce7' : '#fee2e2')) }};
                                color: {{ $trip->trip_status === 'pending' ? '#1e40af' : 
                                   ($trip->trip_status === 'voting' ? '#92400e' : 
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
                            <span class="mobile-location">{{ $trip->pickup_location ?: 'TBD' }}</span>
                            <i class="fas fa-arrow-right mobile-route-icon"></i>
                            <span class="mobile-location">{{ $trip->dropoff_location }}</span>
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
                    <a href="{{ route('admin.trips.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create New Trip
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <table id="tripsTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Trip ID</th>
                                <th>Creator</th>
                                <th>Start Place</th>
                                <th>End Place</th>
                                <th>Departure Time</th>
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
                                    <td>{{ $trip->pickup_location }}</td>
                                    <td>{{ $trip->dropoff_location }}</td>
                                    <td>{{ $trip->planned_departure_time->format('Y-m-d H:i') }}</td>
                                    <td>{{ $trip->max_people }}</td>
                                    <td>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $trip->trip_status === 'pending' ? 'bg-blue-100 text-blue-800' : 
                                               ($trip->trip_status === 'voting' ? 'bg-yellow-100 text-yellow-800' : 
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
                                            <button onclick="deleteTrip({{ $trip->id }})" 
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

    <!-- Modal for Delete Confirmation -->
    <div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; padding: 20px; box-sizing: border-box;">
        <div style="background: white; border-radius: 12px; max-width: 400px; margin: 50px auto; padding: 24px; position: relative; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; background-color: #fee2e2; border-radius: 50%; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 30px; height: 30px; color: #dc2626;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 8px;">Confirm Deletion</h3>
                <p style="color: #6b7280; margin-bottom: 4px;">Are you sure you want to delete this trip?</p>
                <p style="color: #6b7280; font-size: 14px;">Trip ID: <span id="tripInfo" style="font-weight: 600;"></span></p>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button onclick="closeDeleteModal()" style="flex: 1; padding: 10px 20px; background-color: #f3f4f6; color: #374151; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                    Cancel
                </button>
                <button onclick="confirmDeleteSubmit()" style="flex: 1; padding: 10px 20px; background-color: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                    Delete
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
let currentTripId = null;
const isMobile = {{ $isMobile ? 'true' : 'false' }};

if (isMobile) {
    // Mobile search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('mobileSearch');
        const tripCards = document.querySelectorAll('.mobile-trip-card');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                tripCards.forEach(card => {
                    const searchData = card.getAttribute('data-search');
                    if (searchData.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
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
            order: [[4, 'desc']], // 按出發時間排序
            columnDefs: [
                {
                    targets: [7], // Actions column
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

    // Desktop delete function
    function deleteTrip(tripId) {
        if (confirm('Are you sure you want to delete this trip?')) {
            document.getElementById('delete-form-' + tripId).submit();
        }
    }
}

// Modal functions (both mobile and desktop)
function confirmDelete(tripId) {
    currentTripId = tripId;
    document.getElementById('tripInfo').textContent = '#' + tripId;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    currentTripId = null;
}

function confirmDeleteSubmit() {
    if (currentTripId) {
        document.getElementById('delete-form-' + currentTripId).submit();
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeDeleteModal();
    }
});
</script>
@endpush
