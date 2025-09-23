@extends('admin.layout')

@section('title', 'Order Management')
@section('page-title', 'Order Management')

@section('content')
    {{-- 移動版 CSS 重設和專用樣式 --}}
    @if($isMobile)
        <style>
            /* 移動版嚴格CSS重設 - 防止水平滾動 */
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
                padding: 0 !important;
                overflow-x: hidden !important;
            }
            
            /* 移動版搜尋欄樣式 */
            .mobile-search-container {
                background: white !important;
                border-radius: 12px !important;
                padding: 16px !important;
                margin: 16px !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                border: 1px solid #f3f4f6 !important;
                width: calc(100vw - 32px) !important;
                max-width: calc(100vw - 32px) !important;
                box-sizing: border-box !important;
            }
            
            .mobile-search-input {
                width: 100% !important;
                padding: 12px 16px !important;
                border: 2px solid #e5e7eb !important;
                border-radius: 8px !important;
                font-size: 16px !important;
                background: #f9fafb !important;
                transition: all 0.2s !important;
            }
            
            .mobile-search-input:focus {
                outline: none !important;
                border-color: #3b82f6 !important;
                background: white !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            }
            
            /* 移動版統計卡片 */
            .mobile-stats-container {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 12px !important;
                margin: 16px !important;
                width: calc(100vw - 32px) !important;
                max-width: calc(100vw - 32px) !important;
                box-sizing: border-box !important;
            }
            
            .mobile-stat-card {
                background: white !important;
                border-radius: 8px !important;
                padding: 16px !important;
                text-align: center !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                border: 1px solid #f3f4f6 !important;
            }
            
            .mobile-stat-number {
                font-size: 24px !important;
                font-weight: 700 !important;
                color: #1f2937 !important;
                margin: 0 !important;
            }
            
            .mobile-stat-label {
                font-size: 12px !important;
                color: #6b7280 !important;
                font-weight: 500 !important;
                margin: 4px 0 0 0 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
            }
            
            /* 移動版卡片樣式 */
            .mobile-order-card {
                background: white !important;
                border-radius: 12px !important;
                padding: 16px !important;
                margin: 16px !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                border: 1px solid #f3f4f6 !important;
                width: calc(100vw - 32px) !important;
                max-width: calc(100vw - 32px) !important;
                box-sizing: border-box !important;
                transition: all 0.2s !important;
                cursor: pointer !important;
                text-decoration: none !important;
                display: block !important;
            }
            
            .mobile-order-card:hover {
                box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
                transform: translateY(-1px) !important;
            }
            
            .mobile-order-card:active {
                transform: scale(0.98) !important;
                opacity: 0.9 !important;
            }
            
            /* 角色標籤樣式 */
            .role-badge {
                display: inline-block;
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .role-driver {
                background: #dbeafe;
                color: #1e40af;
            }
            
            .role-passenger {
                background: #d1fae5;
                color: #065f46;
            }
            
            /* 確保所有文字元素都不會造成溢出 */
            h1, h2, h3, h4, h5, h6, p, span, div, button, input, select {
                max-width: 100% !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
        </style>
    @else
        <style>
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
        </style>
    @endif

    {{-- ============ 桌面版內容 ============ --}}
    @if(!$isMobile)
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Order Management</h2>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <table id="ordersTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Trip</th>
                            <th>Role</th>
                            <th>Pickup Location</th>
                            <th>Fee (¥)</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->username ?? 'Deleted User' }}</td>
                                <td>{{ $order->trip->pickup_location ?? '-' }} → {{ $order->trip->dropoff_location ?? '-' }}</td>
                                <td>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $order->join_role === 'driver' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($order->join_role) }}
                                    </span>
                                </td>
                                <td>{{ $order->pickup_location ?? '-' }}</td>
                                <td>{{ number_format($order->user_fee, 2) }}</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', ['order' => $order->trip_id . '-' . $order->user_id]) }}" 
                                       class="action-btn action-btn-blue"
                                       title="View Order">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ============ 移動版內容 ============ --}}
    @if($isMobile)
        {{-- 搜尋欄 --}}
        <div class="mobile-search-container">
            <input type="text" id="mobileOrderSearch" placeholder="Search orders..." class="mobile-search-input">
        </div>

        {{-- 統計卡片 --}}
        <div class="mobile-stats-container">
            <div class="mobile-stat-card">
                <div class="mobile-stat-number" id="totalOrdersCount">{{ $orders->total() }}</div>
                <div class="mobile-stat-label">Total Orders</div>
            </div>
            <div class="mobile-stat-card">
                <div class="mobile-stat-number" id="filteredOrdersCount">{{ $orders->total() }}</div>
                <div class="mobile-stat-label">Filtered</div>
            </div>
        </div>

        {{-- 訂單列表 --}}
        <div id="mobileOrdersList">
            @foreach($orders as $order)
                <a href="{{ route('admin.orders.show', ['order' => $order->trip_id . '-' . $order->user_id]) }}" 
                   class="mobile-order-card" 
                   data-search-text="{{ strtolower($order->user->username ?? 'deleted user') }} {{ strtolower($order->trip->pickup_location ?? '') }} {{ strtolower($order->trip->dropoff_location ?? '') }} {{ strtolower($order->join_role) }} {{ strtolower($order->pickup_location ?? '') }}">
                    
                    {{-- 訂單ID和日期 --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 16px; font-weight: 700; color: #1f2937;">
                                Order #{{ $order->id }}
                            </span>
                            <span class="role-badge {{ $order->join_role === 'driver' ? 'role-driver' : 'role-passenger' }}">
                                {{ ucfirst($order->join_role) }}
                            </span>
                        </div>
                        <svg style="width: 16px; height: 16px; fill: #9ca3af;" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    
                    {{-- 用戶和行程信息 --}}
                    <div style="margin-bottom: 12px;">
                        <div style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">User</div>
                        <div style="font-size: 16px; color: #1f2937; font-weight: 600;">
                            {{ $order->user->username ?? 'Deleted User' }}
                        </div>
                    </div>
                    
                    {{-- 行程路線 --}}
                    <div style="margin-bottom: 12px;">
                        <div style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Trip Route</div>
                        <div style="font-size: 14px; color: #1f2937; word-wrap: break-word;">
                            {{ $order->trip->pickup_location ?? '-' }} → {{ $order->trip->dropoff_location ?? '-' }}
                        </div>
                    </div>
                    
                    {{-- 接送地點和費用 --}}
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="flex: 1;">
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">Pickup Location</div>
                            <div style="font-size: 14px; color: #1f2937; word-wrap: break-word;">
                                {{ $order->pickup_location ?? '-' }}
                            </div>
                        </div>
                        <div style="text-align: right; margin-left: 12px;">
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">Fee</div>
                            <div style="font-size: 16px; color: #059669; font-weight: 700;">
                                ¥{{ number_format($order->user_fee, 2) }}
                            </div>
                        </div>
                    </div>
                    
                    {{-- 創建時間 --}}
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #f3f4f6;">
                        <div style="font-size: 12px; color: #6b7280;">
                            Created: {{ $order->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- 移動版分頁 --}}
        @if($orders->hasPages())
            <div style="margin: 20px 16px;">
                {{ $orders->links('pagination.mobile') }}
            </div>
        @endif

        {{-- 移動版 JavaScript --}}
        <script>
            // 移動版搜尋功能
            document.getElementById('mobileOrderSearch').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const orderCards = document.querySelectorAll('.mobile-order-card');
                let visibleCount = 0;
                
                orderCards.forEach(function(card) {
                    const searchText = card.getAttribute('data-search-text');
                    if (searchText.includes(searchTerm)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // 更新過濾統計
                document.getElementById('filteredOrdersCount').textContent = visibleCount;
            });
            
            // 移動版觸控反饋
            document.querySelectorAll('.mobile-order-card').forEach(card => {
                card.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.98)';
                    this.style.opacity = '0.9';
                });
                
                card.addEventListener('touchend', function() {
                    this.style.transform = 'scale(1)';
                    this.style.opacity = '1';
                });
            });
        </script>
    @endif
@endsection

@if(!$isMobile)
@push('scripts')
<script>
$(document).ready(function() {
    $('#ordersTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[6, 'desc']], // 按創建時間排序
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
            search: "Search orders:",
            lengthMenu: "Show _MENU_ orders per page",
            info: "Showing _START_ to _END_ of _TOTAL_ orders",
            infoEmpty: "No orders found",
            infoFiltered: "(filtered from _MAX_ total orders)",
            emptyTable: "No order data available",
            zeroRecords: "No orders match your search criteria",
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
@endif
