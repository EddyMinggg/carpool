@extends('admin.layout')

@section('title', 'Coupon Management - List')
@section('page-title', 'Coupon Management')

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
                grid-template-columns: 1fr 1fr 1fr !important;
                gap: 12px !important;
                margin: 16px !important;
                width: calc(100vw - 32px) !important;
                max-width: calc(100vw - 32px) !important;
                box-sizing: border-box !important;
            }
            
            .mobile-stat-card {
                background: white !important;
                border-radius: 8px !important;
                padding: 12px !important;
                text-align: center !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                border: 1px solid #f3f4f6 !important;
            }
            
            .mobile-stat-number {
                font-size: 20px !important;
                font-weight: 700 !important;
                color: #1f2937 !important;
                margin: 0 !important;
            }
            
            .mobile-stat-label {
                font-size: 10px !important;
                color: #6b7280 !important;
                font-weight: 500 !important;
                margin: 4px 0 0 0 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
            }
            
            /* 移動版卡片樣式 */
            .mobile-coupon-card {
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
            
            .mobile-coupon-card:hover {
                box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
                transform: translateY(-1px) !important;
            }
            
            .mobile-coupon-card:active {
                transform: scale(0.98) !important;
                opacity: 0.9 !important;
            }
            
            /* 狀態標籤樣式 */
            .status-badge {
                display: inline-block;
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .status-enabled {
                background: #d1fae5;
                color: #065f46;
            }
            
            .status-disabled {
                background: #fee2e2;
                color: #991b1b;
            }
            
            /* 浮動新增按鈕 */
            .floating-add-btn {
                position: fixed !important;
                bottom: 80px !important;
                right: 20px !important;
                width: 56px !important;
                height: 56px !important;
                background: #3b82f6 !important;
                border-radius: 50% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
                z-index: 1000 !important;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                border: none !important;
                text-decoration: none !important;
            }
            
            .floating-add-btn:hover {
                background: #2563eb !important;
                transform: scale(1.1) !important;
                box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6) !important;
            }
            
            .floating-add-btn i {
                color: white !important;
                font-size: 20px !important;
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
    @endif

    {{-- ============ 桌面版內容 ============ --}}
    @if(!$isMobile)
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Coupon Management</h2>
                <a href="{{ route('admin.coupons.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create New Coupon
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <table id="couponsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Valid From</th>
                            <th>Valid To</th>
                            <th>Enabled</th>
                            <th>Usage Limit</th>
                            <th>Used Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td>{{ $coupon->code }}</td>
                                <td>¥{{ number_format($coupon->discount_amount, 2) }}</td>
                                <td>{{ $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : '-' }}</td>
                                <td>{{ $coupon->valid_to ? $coupon->valid_to->format('Y-m-d') : '-' }}</td>
                                <td>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $coupon->enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $coupon->enabled ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>{{ $coupon->usage_limit ?? '-' }}</td>
                                <td>{{ $coupon->used_count }}</td>
                                <td>
                                    <div class="flex space-x-1">
                                        <a href="{{ route('admin.coupons.show', $coupon->id) }}" 
                                           class="action-btn action-btn-blue"
                                           title="View Coupon">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" 
                                           class="action-btn action-btn-yellow"
                                           title="Edit Coupon">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="showDeleteModal(document.getElementById('deleteForm{{ $coupon->id }}'), '{{ $coupon->code }}')" 
                                                class="action-btn action-btn-red"
                                                title="Delete Coupon">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="deleteForm{{ $coupon->id }}" action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display: none;">
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
    @endif

    {{-- ============ 移動版內容 ============ --}}
    @if($isMobile)
        {{-- 搜尋欄 --}}
        <div class="mobile-search-container">
            <input type="text" id="mobileCouponSearch" placeholder="Search coupons..." class="mobile-search-input">
        </div>

        {{-- 統計卡片 --}}
        <div class="mobile-stats-container">
            <div class="mobile-stat-card">
                <div class="mobile-stat-number" id="totalCouponsCount">{{ $coupons->total() }}</div>
                <div class="mobile-stat-label">Total</div>
            </div>
            <div class="mobile-stat-card">
                <div class="mobile-stat-number" id="enabledCouponsCount">{{ $coupons->where('enabled', true)->count() }}</div>
                <div class="mobile-stat-label">Enabled</div>
            </div>
            <div class="mobile-stat-card">
                <div class="mobile-stat-number" id="filteredCouponsCount">{{ $coupons->total() }}</div>
                <div class="mobile-stat-label">Filtered</div>
            </div>
        </div>

        {{-- 優惠券列表 --}}
        <div id="mobileCouponsList">
            @foreach($coupons as $coupon)
                <a href="{{ route('admin.coupons.show', $coupon->id) }}" 
                   class="mobile-coupon-card" 
                   data-search-text="{{ strtolower($coupon->code) }} {{ strtolower($coupon->discount_amount) }} {{ $coupon->enabled ? 'enabled' : 'disabled' }}">
                    
                    {{-- 優惠券代碼和狀態 --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 18px; font-weight: 700; color: #1f2937;">
                                {{ $coupon->code }}
                            </span>
                            <span class="status-badge {{ $coupon->enabled ? 'status-enabled' : 'status-disabled' }}">
                                {{ $coupon->enabled ? 'ENABLED' : 'DISABLED' }}
                            </span>
                        </div>
                        <svg style="width: 16px; height: 16px; fill: #9ca3af;" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    
                    {{-- 折扣金額 --}}
                    <div style="margin-bottom: 12px;">
                        <div style="font-size: 14px; color: #6b7280; margin-bottom: 4px;">Discount Amount</div>
                        <div style="font-size: 24px; color: #059669; font-weight: 700;">
                            ¥{{ number_format($coupon->discount_amount, 2) }}
                        </div>
                    </div>
                    
                    {{-- 有效期間 --}}
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <div style="flex: 1;">
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">Valid From</div>
                            <div style="font-size: 14px; color: #1f2937;">
                                {{ $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : '-' }}
                            </div>
                        </div>
                        <div style="flex: 1; text-align: right;">
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">Valid To</div>
                            <div style="font-size: 14px; color: #1f2937;">
                                {{ $coupon->valid_to ? $coupon->valid_to->format('Y-m-d') : '-' }}
                            </div>
                        </div>
                    </div>
                    
                    {{-- 使用統計 --}}
                    <div style="display: flex; justify-content: space-between; padding-top: 12px; border-top: 1px solid #f3f4f6;">
                        <div>
                            <div style="font-size: 12px; color: #6b7280;">Usage Limit</div>
                            <div style="font-size: 14px; color: #1f2937; font-weight: 600;">
                                {{ $coupon->usage_limit ?? 'Unlimited' }}
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 12px; color: #6b7280;">Used Count</div>
                            <div style="font-size: 14px; color: #1f2937; font-weight: 600;">
                                {{ $coupon->used_count }}
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- 浮動新增按鈕 --}}
        <a href="{{ route('admin.coupons.create') }}" class="floating-add-btn">
            <i class="fas fa-plus"></i>
        </a>

        {{-- 移動版分頁 --}}
        @if($coupons->hasPages())
            <div style="margin: 20px 16px;">
                {{ $coupons->links('pagination.mobile') }}
            </div>
        @endif

        {{-- 移動版 JavaScript --}}
        <script>
            // 移動版搜尋功能
            document.getElementById('mobileCouponSearch').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const couponCards = document.querySelectorAll('.mobile-coupon-card');
                let visibleCount = 0;
                
                couponCards.forEach(function(card) {
                    const searchText = card.getAttribute('data-search-text');
                    if (searchText.includes(searchTerm)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // 更新過濾統計
                document.getElementById('filteredCouponsCount').textContent = visibleCount;
            });
            
            // 移動版觸控反饋
            document.querySelectorAll('.mobile-coupon-card').forEach(card => {
                card.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.98)';
                    this.style.opacity = '0.9';
                });
                
                card.addEventListener('touchend', function() {
                    this.style.transform = 'scale(1)';
                    this.style.opacity = '1';
                });
            });
            
            // 浮動按鈕觸控反饋
            document.querySelector('.floating-add-btn').addEventListener('touchstart', function() {
                this.style.transform = 'scale(1.05)';
            });
            
            document.querySelector('.floating-add-btn').addEventListener('touchend', function() {
                this.style.transform = 'scale(1.1)';
            });
        </script>
    @endif

    {{-- Delete Confirmation Modal for Desktop --}}
    @if(!$isMobile)
        <div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 8px; max-width: 400px; width: 90%; margin: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3); max-height: 80vh; overflow-y: auto;">
                <!-- Modal Header -->
                <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #dc2626;">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        Confirm Delete
                    </h3>
                </div>
                
                <!-- Modal Body -->
                <div style="padding: 20px;">
                    <p style="margin: 0 0 16px 0; color: #374151; line-height: 1.5; font-size: 16px;">
                        Are you sure you want to delete this coupon? This action cannot be undone and will permanently remove the coupon.
                    </p>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px; margin-bottom: 16px;">
                        <p style="margin: 0; font-size: 14px; color: #dc2626;">
                            <i class="fas fa-info-circle" style="margin-right: 6px;"></i>
                            Coupon: <strong id="couponToDelete"></strong>
                        </p>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div style="padding: 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; gap: 12px;">
                    <button onclick="closeDeleteModal()" 
                            style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px; transition: background-color 0.2s; flex: 1;"
                            onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        Cancel
                    </button>
                    <button onclick="confirmDelete()" 
                            style="padding: 12px 24px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px; transition: background-color 0.2s; flex: 1;"
                            onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                        <i class="fas fa-trash" style="margin-right: 6px;"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <script>
            let deleteForm = null;
            let couponCode = '';

            function showDeleteModal(form, code) {
                deleteForm = form;
                couponCode = code;
                document.getElementById('couponToDelete').textContent = couponCode;
                
                const modal = document.getElementById('deleteModal');
                modal.style.display = 'flex';
                
                // Prevent body scroll when modal is open
                document.body.style.overflow = 'hidden';
                
                return false; // Prevent form submission
            }

            function closeDeleteModal() {
                const modal = document.getElementById('deleteModal');
                modal.style.display = 'none';
                
                // Restore body scroll
                document.body.style.overflow = '';
                
                deleteForm = null;
                couponCode = '';
            }

            function confirmDelete() {
                if (deleteForm) {
                    deleteForm.submit();
                }
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && document.getElementById('deleteModal').style.display === 'flex') {
                    closeDeleteModal();
                }
            });
        </script>
    @endif
@endsection

@if(!$isMobile)
@push('scripts')
<script>
$(document).ready(function() {
    $('#couponsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[0, 'desc']],
        columnDefs: [
            {
                targets: [8], // Actions column
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
            search: "Search coupons:",
            lengthMenu: "Show _MENU_ coupons per page",
            info: "Showing _START_ to _END_ of _TOTAL_ coupons",
            infoEmpty: "No coupons found",
            infoFiltered: "(filtered from _MAX_ total coupons)",
            emptyTable: "No coupon data available",
            zeroRecords: "No coupons match your search criteria",
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
