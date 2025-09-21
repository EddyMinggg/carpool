@extends('admin.layout')

@section('content')
    <style>
        /* 統計卡片樣式 */
        .stats-card-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .stats-card-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .stats-card-yellow {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .stats-card-pink {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .stats-icon-bg {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            padding: 1rem;
        }
        
        /* DataTables 按鈕美化 - 統一配色方案 */
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
        
        .dt-button:active {
            transform: translateY(0) !important;
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
        }
        
        /* 圖標顏色差異化 - 保持統一背景，僅圖標有色彩 */
        .dt-button:nth-child(1) i { /* Copy */
            color: #8b5cf6 !important;
        }
        
        .dt-button:nth-child(2) i { /* CSV */
            color: #10b981 !important;
        }
        
        .dt-button:nth-child(3) i { /* Excel */
            color: #059669 !important;
        }
        
        .dt-button:nth-child(4) i { /* PDF */
            color: #ef4444 !important;
        }
        
        .dt-button:nth-child(5) i { /* Print */
            color: #6366f1 !important;
        }
        
        /* hover 時圖標變白色 */
        .dt-button:hover i {
            color: white !important;
        }
        
        /* 響應式設計 */
        @media (max-width: 768px) {
            .dt-buttons {
                justify-content: center;
            }
            
            .dt-button {
                flex: 1;
                min-width: 80px !important;
                padding: 8px 12px !important;
                font-size: 0.75rem !important;
            }
            
            .dt-button i {
                display: none;
            }
        }
        
        /* Focus 狀態 */
        .dt-button:focus {
            outline: 2px solid #3b82f6 !important;
            outline-offset: 2px !important;
        }
        
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
    </style>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
        <p class="text-gray-500 mt-1">Overview of your carpool system performance</p>
    </div>
    
    <!-- Stats Summary - 兩行，每行兩個 -->
    <div class="mb-6">
        <!-- 第一行 -->
        <div style="display: flex; gap: 2rem; margin-bottom: 1.5rem;">
            <div class="stats-card-blue" style="flex: 1;">
                <div class="flex items-center justify-between">
                    <div>
                        <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Total Users</p>
                        <p style="font-size: 1.875rem; font-weight: bold;">{{ $totalUsers }}</p>
                    </div>
                    <div class="stats-icon-bg">
                        <i class="fas fa-users" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-green" style="flex: 1;">
                <div class="flex items-center justify-between">
                    <div>
                        <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Total Trips</p>
                        <p style="font-size: 1.875rem; font-weight: bold;">{{ $totalTrips }}</p>
                    </div>
                    <div class="stats-icon-bg">
                        <i class="fas fa-car" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 第二行 -->
        <div style="display: flex; gap: 2rem;">
            <div class="stats-card-yellow" style="flex: 1;">
                <div class="flex items-center justify-between">
                    <div>
                        <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Pending Trips</p>
                        <p style="font-size: 1.875rem; font-weight: bold;">{{ $pendingTrips }}</p>
                    </div>
                    <div class="stats-icon-bg">
                        <i class="fas fa-clock" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-pink" style="flex: 1;">
                <div class="flex items-center justify-between">
                    <div>
                        <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Coupons Used</p>
                        <p style="font-size: 1.875rem; font-weight: bold;">{{ $couponUsed }}</p>
                    </div>
                    <div class="stats-icon-bg">
                        <i class="fas fa-ticket-alt" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Trips Table with DataTable -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Upcoming Trips</h2>
                <a href="{{ route('admin.trips.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                    View all <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
            
            <table id="tripsTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Trip ID</th>
                        <th>Destination</th>
                        <th>Departure Time</th>
                        <th>Capacity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($upcomingTrips->count() > 0)
                        @foreach($upcomingTrips as $trip)
                            <tr>
                                <td>{{ $trip->id }}</td>
                                <td>{{ $trip->dropoff_location }}</td>
                                <td>{{ $trip->planned_departure_time->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="width: 60px; height: 8px; background-color: #e5e7eb; border-radius: 4px; position: relative;">
                                            <div style="width: {{ $trip->max_people > 0 ? round(($trip->joins_count / $trip->max_people) * 100) : 0 }}%; height: 100%; background-color: #3b82f6; border-radius: 4px;"></div>
                                        </div>
                                        <span style="font-size: 0.75rem; color: #6b7280;">{{ $trip->joins_count }}/{{ $trip->max_people }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 9999px; background-color: #dbeafe; color: #1e40af;">
                                        Upcoming
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // 初始化 DataTable
    $('#tripsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        order: [[2, 'asc']], // 按出發時間排序
        columnDefs: [
            {
                targets: [3, 4], // Capacity and Status columns
                orderable: false
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
            emptyTable: "No upcoming trips scheduled at the moment",
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
</script>
@endpush
