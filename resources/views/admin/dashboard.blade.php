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
        
        /* === 移動設備響應式設計 === */
        
        /* 統計卡片的移動設備優化 */
        @media (max-width: 768px) {
            /* 頁面標題移動版 */
            .dashboard-header {
                text-align: center;
                margin-bottom: 1.5rem;
            }
            
            .dashboard-header h2 {
                font-size: 1.5rem !important;
                margin-bottom: 0.5rem;
            }
            
            .dashboard-header p {
                font-size: 0.875rem;
            }
            
            /* 統計卡片移動版 - 改為單列顯示 */
            .stats-grid {
                display: block !important;
            }
            
            .stats-row {
                display: block !important;
                margin-bottom: 0 !important;
            }
            
            .stats-card-blue,
            .stats-card-green,
            .stats-card-yellow,
            .stats-card-pink {
                margin-bottom: 1rem !important;
                padding: 1.25rem !important;
            }
            
            .stats-card-blue .flex,
            .stats-card-green .flex,
            .stats-card-yellow .flex,
            .stats-card-pink .flex {
                flex-direction: row !important;
                align-items: center !important;
                justify-content: space-between !important;
            }
            
            .stats-icon-bg {
                padding: 0.75rem !important;
                flex-shrink: 0;
            }
            
            .stats-icon-bg i {
                font-size: 1.25rem !important;
            }
            
            /* 統計數字在移動設備上的調整 */
            .stats-card-blue p:last-child,
            .stats-card-green p:last-child,
            .stats-card-yellow p:last-child,
            .stats-card-pink p:last-child {
                font-size: 1.5rem !important;
                margin: 0 !important;
            }
            
            .stats-card-blue p:first-child,
            .stats-card-green p:first-child,
            .stats-card-yellow p:first-child,
            .stats-card-pink p:first-child {
                font-size: 0.8rem !important;
                margin-bottom: 0.25rem !important;
            }
            
            /* 表格容器移動版 */
            .table-container {
                padding: 1rem !important;
                margin: 0 -0.5rem;
                border-radius: 0.5rem !important;
            }
            
            .table-header {
                flex-direction: column !important;
                align-items: flex-start !important;
                margin-bottom: 1rem !important;
                text-align: center;
            }
            
            .table-header h2 {
                font-size: 1.125rem !important;
                margin-bottom: 0.5rem;
            }
            
            .table-header a {
                align-self: center;
                margin-top: 0.5rem;
            }
            
            /* DataTables 移動版優化 */
            .dataTables_wrapper {
                font-size: 0.875rem !important;
            }
            
            /* 搜索框和長度選擇器移動版 */
            .dataTables_filter,
            .dataTables_length {
                text-align: center !important;
                margin-bottom: 1rem !important;
            }
            
            .dataTables_filter input,
            .dataTables_length select {
                padding: 0.5rem !important;
                border-radius: 0.375rem !important;
                border: 1px solid #d1d5db !important;
                font-size: 0.875rem !important;
            }
            
            /* 表格移動版 - 隱藏部分列 */
            #tripsTable th:nth-child(1),
            #tripsTable td:nth-child(1) {
                display: none; /* 隱藏 Trip ID */
            }
            
            #tripsTable th:nth-child(4),
            #tripsTable td:nth-child(4) {
                display: none; /* 隱藏 Capacity */
            }
            
            /* 保留的列進行優化 */
            #tripsTable th,
            #tripsTable td {
                padding: 0.5rem 0.25rem !important;
                font-size: 0.8rem !important;
                vertical-align: middle !important;
            }
            
            #tripsTable th:nth-child(2),
            #tripsTable td:nth-child(2) {
                width: 40% !important; /* Destination */
            }
            
            #tripsTable th:nth-child(3),
            #tripsTable td:nth-child(3) {
                width: 35% !important; /* Departure Time */
                font-size: 0.75rem !important;
            }
            
            #tripsTable th:nth-child(5),
            #tripsTable td:nth-child(5) {
                width: 25% !important; /* Status */
                text-align: center !important;
            }
            
            /* Status badge 移動版 */
            #tripsTable td:nth-child(5) span {
                padding: 0.125rem 0.375rem !important;
                font-size: 0.625rem !important;
                display: inline-block;
                white-space: nowrap;
            }
            
            /* 分頁信息移動版 */
            .dataTables_info,
            .dataTables_paginate {
                text-align: center !important;
                font-size: 0.75rem !important;
            }
            
            .dataTables_paginate .paginate_button {
                padding: 0.25rem 0.5rem !important;
                margin: 0 0.125rem !important;
                font-size: 0.75rem !important;
            }
            
            /* 添加移動設備專用的快速操作區 */
            .mobile-quick-actions {
                display: flex;
                justify-content: space-around;
                background: #f8fafc;
                padding: 1rem;
                border-radius: 0.5rem;
                margin: 1rem 0;
                border: 1px solid #e2e8f0;
            }
            
            .mobile-quick-action {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-decoration: none;
                color: #6b7280;
                transition: color 0.2s;
            }
            
            .mobile-quick-action:hover {
                color: #3b82f6;
            }
            
            .mobile-quick-action i {
                font-size: 1.25rem;
                margin-bottom: 0.25rem;
            }
            
            .mobile-quick-action span {
                font-size: 0.75rem;
                font-weight: 500;
            }
        }
        
        /* 小屏幕設備 (< 640px) 進一步優化 */
        @media (max-width: 640px) {
            .dashboard-header h2 {
                font-size: 1.25rem !important;
            }
            
            .stats-card-blue,
            .stats-card-green,
            .stats-card-yellow,
            .stats-card-pink {
                padding: 1rem !important;
            }
            
            .table-container {
                padding: 0.75rem !important;
                margin: 0 -0.25rem;
            }
            
            /* 進一步簡化表格 */
            #tripsTable th,
            #tripsTable td {
                padding: 0.375rem 0.125rem !important;
                font-size: 0.75rem !important;
            }
            
            /* 時間格式在小屏幕上簡化 */
            .mobile-time-format {
                display: block;
                font-size: 0.7rem !important;
                line-height: 1.2;
            }
        }
        
        /* 橫屏手機優化 */
        @media (max-width: 896px) and (orientation: landscape) {
            .stats-row {
                display: flex !important;
                gap: 1rem !important;
            }
            
            .stats-card-blue,
            .stats-card-green,
            .stats-card-yellow,
            .stats-card-pink {
                flex: 1;
                margin-bottom: 0.5rem !important;
            }
        }
    </style>

    <div class="mb-6 dashboard-header">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
        <p class="text-gray-500 mt-1">Overview of your carpool system performance</p>
    </div>
    
    <!-- Stats Summary - 響應式網格 -->
    <div class="mb-6 stats-grid">
        <!-- 第一行 -->
        <div class="stats-row" style="display: flex; gap: 2rem; margin-bottom: 1.5rem;">
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
        <div class="stats-row" style="display: flex; gap: 2rem;">
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

    <!-- 移動設備快速操作區 -->
    <div class="mobile-quick-actions d-md-none">
        <a href="{{ route('admin.users.index') }}" class="mobile-quick-action">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        <a href="{{ route('admin.trips.index') }}" class="mobile-quick-action">
            <i class="fas fa-car"></i>
            <span>Trips</span>
        </a>
        <a href="{{ route('admin.coupons.index') }}" class="mobile-quick-action">
            <i class="fas fa-ticket-alt"></i>
            <span>Coupons</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="mobile-quick-action">
            <i class="fas fa-receipt"></i>
            <span>Orders</span>
        </a>
    </div>

    <!-- Upcoming Trips Table with DataTable -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden table-container">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4 table-header">
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
    // 檢測是否為移動設備
    function isMobile() {
        return window.innerWidth <= 768;
    }
    
    // 動態配置 DataTable 設置
    function getDataTableConfig() {
        const baseConfig = {
            responsive: true,
            pageLength: isMobile() ? 5 : 10,
            lengthMenu: isMobile() ? [[5, 10, 25], [5, 10, 25]] : [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            order: [[2, 'asc']], // 按出發時間排序
            columnDefs: [
                {
                    targets: [3, 4], // Capacity and Status columns
                    orderable: false
                }
            ],
            dom: isMobile() ? 'frtlip' : 'Bfrtlip', // 移動設備隱藏按鈕
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
        };
        
        // 僅在桌面版添加按鈕
        if (!isMobile()) {
            baseConfig.buttons = [
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
            ];
        }
        
        return baseConfig;
    }
    
    // 初始化 DataTable
    const table = $('#tripsTable').DataTable(getDataTableConfig());
    
    // 處理窗口大小變化
    $(window).on('resize', function() {
        // 重新計算 DataTable 布局
        table.columns.adjust().responsive.recalc();
        
        // 如果屏幕大小變化跨越了移動/桌面邊界，重新初始化表格
        const currentIsMobile = isMobile();
        if (window.lastIsMobile !== currentIsMobile) {
            window.lastIsMobile = currentIsMobile;
            
            // 銷毀當前表格並重新初始化
            table.destroy();
            $('#tripsTable').DataTable(getDataTableConfig());
        }
    });
    
    // 設置初始狀態
    window.lastIsMobile = isMobile();
    
    // 移動設備時間格式優化
    if (isMobile()) {
        // 為時間列添加移動設備友好的格式
        $('#tripsTable tbody tr').each(function() {
            const timeCell = $(this).find('td:nth-child(3)');
            const timeText = timeCell.text().trim();
            
            if (timeText) {
                // 將 "YYYY-MM-DD HH:MM" 格式轉換為更適合移動設備的格式
                const parts = timeText.split(' ');
                if (parts.length === 2) {
                    const datePart = parts[0].split('-');
                    const timePart = parts[1];
                    
                    if (datePart.length === 3) {
                        const monthDay = `${datePart[1]}/${datePart[2]}`;
                        const formattedTime = `<div class="mobile-time-format">${monthDay}<br>${timePart}</div>`;
                        timeCell.html(formattedTime);
                    }
                }
            }
        });
    }
    
    // 移動設備觸控優化
    if (isMobile()) {
        // 增加觸控目標大小
        $('#tripsTable tbody tr').on('touchstart', function() {
            $(this).addClass('bg-gray-50');
        }).on('touchend', function() {
            $(this).removeClass('bg-gray-50');
        });
        
        // 優化搜索框在移動設備上的行為
        $('.dataTables_filter input').attr('placeholder', 'Search...');
        
        // 為移動設備添加快速滾動到頂部按鈕
        if ($('#tripsTable tbody tr').length > 5) {
            $('<button>')
                .addClass('btn btn-sm btn-secondary position-fixed')
                .css({
                    'bottom': '20px',
                    'right': '20px',
                    'z-index': '1000',
                    'border-radius': '50%',
                    'width': '50px',
                    'height': '50px',
                    'display': 'none'
                })
                .html('<i class="fas fa-chevron-up"></i>')
                .attr('id', 'scrollToTop')
                .appendTo('body')
                .on('click', function() {
                    $('html, body').animate({ scrollTop: 0 }, 300);
                });
            
            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 300) {
                    $('#scrollToTop').fadeIn();
                } else {
                    $('#scrollToTop').fadeOut();
                }
            });
        }
    }
});
</script>
@endpush
