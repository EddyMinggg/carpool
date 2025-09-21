@extends('super-admin.layout')

@section('title', 'Admin Management')

@section('content')
    <style>
        /* DataTable 按鈕樣式 */
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
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
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
        @media (max-width: 768px) {
            /* 頁面標題移動版 */
            .admin-header {
                text-align: center;
                margin-bottom: 1.5rem;
            }
            
            .admin-header h1 {
                font-size: 1.5rem !important;
                margin-bottom: 0.5rem;
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
            .stats-card-purple,
            .stats-card-red {
                margin-bottom: 1rem !important;
                padding: 1.25rem !important;
            }
            
            .stats-icon-bg {
                padding: 0.75rem !important;
                flex-shrink: 0;
            }
            
            .stats-icon-bg i {
                font-size: 1.25rem !important;
            }
            
            /* 角色過濾器移動版 */
            .role-filter {
                margin-bottom: 1.5rem !important;
                text-align: center;
            }
            
            .role-filter .btn {
                margin: 0.25rem !important;
                padding: 0.5rem 1rem !important;
                font-size: 0.875rem !important;
            }
            
            /* Add Admin 按鈕移動版 */
            .add-admin-btn {
                width: 100% !important;
                text-align: center;
                margin-bottom: 1rem !important;
                padding: 0.75rem !important;
                font-size: 0.875rem !important;
            }
            
            /* 表格容器移動版 */
            .table-container {
                padding: 1rem !important;
                margin: 0 -0.5rem;
                border-radius: 0.5rem !important;
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
            #adminsTable th:nth-child(1),
            #adminsTable td:nth-child(1) {
                display: none; /* 隱藏 ID */
            }
            
            #adminsTable th:nth-child(3),
            #adminsTable td:nth-child(3) {
                display: none; /* 隱藏 Email */
            }
            
            #adminsTable th:nth-child(5),
            #adminsTable td:nth-child(5) {
                display: none; /* 隱藏 Created */
            }
            
            /* 保留的列進行優化 */
            #adminsTable th,
            #adminsTable td {
                padding: 0.5rem 0.25rem !important;
                font-size: 0.8rem !important;
                vertical-align: middle !important;
            }
            
            #adminsTable th:nth-child(2),
            #adminsTable td:nth-child(2) {
                width: 35% !important; /* Name */
            }
            
            #adminsTable th:nth-child(4),
            #adminsTable td:nth-child(4) {
                width: 25% !important; /* Role */
                text-align: center !important;
            }
            
            #adminsTable th:nth-child(6),
            #adminsTable td:nth-child(6) {
                width: 40% !important; /* Actions */
                text-align: center !important;
            }
            
            /* Action 按鈕移動版 */
            .action-btn {
                display: block !important;
                margin: 0.125rem 0 !important;
                padding: 0.375rem 0.5rem !important;
                font-size: 0.75rem !important;
                width: 100% !important;
                text-align: center !important;
            }
            
            /* Role badge 移動版 */
            .badge {
                padding: 0.125rem 0.5rem !important;
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
            .admin-header h1 {
                font-size: 1.25rem !important;
            }
            
            .stats-card-blue,
            .stats-card-green,
            .stats-card-purple,
            .stats-card-red {
                padding: 1rem !important;
            }
            
            .table-container {
                padding: 0.75rem !important;
                margin: 0 -0.25rem;
            }
            
            /* 進一步簡化表格 */
            #adminsTable th,
            #adminsTable td {
                padding: 0.375rem 0.125rem !important;
                font-size: 0.75rem !important;
            }
            
            /* Action 按鈕堆疊顯示 */
            .mobile-actions {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
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
            .stats-card-purple,
            .stats-card-red {
                flex: 1;
                margin-bottom: 0.5rem !important;
            }
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
        
        /* 統計卡片樣式 */
        .stats-card-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .stats-card-red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
    </style>

    <div class="mb-6 admin-header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Admin Management</h1>
            <a href="{{ route('super-admin.admins.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors add-admin-btn">
                <i class="fas fa-plus mr-2"></i>Create New Admin
            </a>
        </div>
    </div>
    
    <!-- Stats Summary - 響應式網格 -->
    <div class="mb-6 stats-grid">
        <div class="stats-row" style="display: flex; gap: 4rem; margin-bottom: 1.5rem;">
            <div class="stats-card-red" style="flex: 1;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-opacity-80 text-sm font-medium mb-1">Super Admins</p>
                        <p class="text-3xl font-bold" id="super-admins">
                            {{ $admins->where('is_admin', 2)->count() }}
                        </p>
                    </div>
                    <div class="stats-icon-bg">
                        <i class="fas fa-crown text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-blue" style="flex: 1;">
                <div class="flex items-center justify-between">
                    <div>
                    <p class="text-white text-opacity-80 text-sm font-medium mb-1">Admins</p>
                    <p class="text-3xl font-bold" id="regular-admins">
                        {{ $admins->where('is_admin', 1)->count() }}
                    </p>
                </div>
                <div class="stats-icon-bg">
                    <i class="fas fa-user-shield text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

        <!-- 移動設備快速操作區 -->
    <div class="mobile-quick-actions d-md-none">
        <a href="{{ route('super-admin.admins.create') }}" class="mobile-quick-action">
            <i class="fas fa-user-plus"></i>
            <span>Add Admin</span>
        </a>
        <a href="#" onclick="$('#role-filter').val('Admin').change()" class="mobile-quick-action">
            <i class="fas fa-user-tie"></i>
            <span>Admins</span>
        </a>
        <a href="#" onclick="$('#role-filter').val('Super Admin').change()" class="mobile-quick-action">
            <i class="fas fa-crown"></i>
            <span>Super Admins</span>
        </a>
        <a href="#" onclick="$('#role-filter').val('').change()" class="mobile-quick-action">
            <i class="fas fa-users"></i>
            <span>All</span>
        </a>
    </div>

    <!-- Admin Table with DataTable -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden table-container">
        <div class="p-6">
            <!-- Role Filter -->
            <div class="mb-4 role-filter">
                <label for="role-filter" class="block text-sm font-medium text-gray-700 mb-2">Filter by Role:</label>
                <select id="role-filter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Roles</option>
                    <option value="Admin">Administrators</option>
                    <option value="Super Admin">Super Administrators</option>
                </select>
            </div>
            
            <table id="adminsTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>{{ $admin->username }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $admin->isSuperAdmin() ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $admin->getRoleName() }}
                                </span>
                            </td>
                            <td>
                                <div class="flex space-x-1">
                                    <a href="{{ route('super-admin.admins.show', $admin->id) }}" 
                                       class="action-btn action-btn-blue"
                                       title="View Admin">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('super-admin.admins.edit', $admin->id) }}" 
                                       class="action-btn action-btn-yellow"
                                       title="Edit Admin">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($admin->id !== Auth::user()->id)
                                        <button onclick="deleteAdmin({{ $admin->id }})" 
                                                class="action-btn action-btn-red"
                                                title="Delete Admin">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $admin->id }}" action="{{ route('super-admin.admins.destroy', $admin->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table; // 在全局作用域聲明
    
    // 檢測是否為移動設備
    function isMobile() {
        return window.innerWidth <= 768;
    }
    
    // 動態配置 DataTable 設置
    function getDataTableConfig() {
        const baseConfig = {
            responsive: true,
            pageLength: isMobile() ? 5 : 10,
            lengthMenu: isMobile() ? [[5, 10, 25], [5, 10, 25]] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[0, 'asc']], // 按 ID 排序
            columnDefs: [
                {
                    targets: [5], // Actions column
                    orderable: false,
                    searchable: false
                }
            ],
            dom: isMobile() ? 'frtlip' : 'Bfrtlip', // 移動設備隱藏按鈕
            language: {
                search: "Search admins:",
                lengthMenu: "Show _MENU_ admins per page",
                info: "Showing _START_ to _END_ of _TOTAL_ admins",
                infoEmpty: "No admins found",
                infoFiltered: "(filtered from _MAX_ total admins)",
                emptyTable: "No administrators found",
                zeroRecords: "No admins match your search criteria",
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
    table = $('#adminsTable').DataTable(getDataTableConfig());
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
            search: "Search admins:",
            lengthMenu: "Show _MENU_ admins per page",
            info: "Showing _START_ to _END_ of _TOTAL_ admins",
            infoEmpty: "No admins found",
            infoFiltered: "(filtered from _MAX_ total admins)",
            emptyTable: "No admin users found",
            zeroRecords: "No admins match your search criteria",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        drawCallback: function(settings) {
            // 更新統計數據
            updateStats();
        }
    });
    
    // 角色過濾器事件
    $('#role-filter').on('change', function() {
        var selectedRole = $(this).val();
        console.log('Filter changed to:', selectedRole);
        
        if (selectedRole === '') {
            // 清除所有過濾
            table.search('').columns().search('').draw();
        } else {
            // 在角色列(第4列)搜索
            table.column(4).search(selectedRole, false, false).draw();
        }
    });
    
    // 更新統計數據函數
    function updateStats() {
        try {
            if (!table) return;
            
            var info = table.page.info();
            var visibleRows = table.rows({search: 'applied'});
            
            var superAdmins = 0;
            var regularAdmins = 0;
            
            // 遍歷當前顯示的行
            visibleRows.every(function(rowIdx, tableLoop, rowLoop) {
                var data = this.data();
                var roleHtml = data[4]; // Role 列現在是第 4 列 (0-indexed)
                var roleText = $(roleHtml).text().trim();
                
                if (roleText === 'Super Admin') {
                    superAdmins++;
                } else if (roleText === 'Admin') {
                    regularAdmins++;
                }
            });
            
            $('#super-admins').text(superAdmins);
            $('#regular-admins').text(regularAdmins);
        } catch (error) {
            console.error('Error updating stats:', error);
            // 回退到原始數據
            $('#super-admins').text('{{ $admins->where("is_admin", 2)->count() }}');
            $('#regular-admins').text('{{ $admins->where("is_admin", 1)->count() }}');
        }
    }
    
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
            table = $('#adminsTable').DataTable(getDataTableConfig());
            
            // 重新綁定角色過濾器
            $('#role-filter').off('change').on('change', function() {
                var selectedRole = $(this).val();
                console.log('Filter changed to:', selectedRole);
                
                if (selectedRole === '') {
                    table.search('').columns().search('').draw();
                } else {
                    table.column(4).search(selectedRole, false, false).draw();
                }
            });
        }
    });
    
    // 設置初始狀態
    window.lastIsMobile = isMobile();
    
    // 移動設備觸控優化
    if (isMobile()) {
        // 增加觸控目標大小
        $('#adminsTable tbody tr').on('touchstart', function() {
            $(this).addClass('bg-gray-50');
        }).on('touchend', function() {
            $(this).removeClass('bg-gray-50');
        });
        
        // 優化搜索框在移動設備上的行為
        $('.dataTables_filter input').attr('placeholder', 'Search admins...');
        
        // 為移動設備添加快速滾動到頂部按鈕
        if ($('#adminsTable tbody tr').length > 5) {
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
        
        // 移動設備Action按鈕優化
        $('#adminsTable tbody tr').each(function() {
            const actionCell = $(this).find('td:last-child');
            actionCell.addClass('mobile-actions');
        });
    }
});

function deleteAdmin(adminId) {
    if (confirm('Are you sure you want to delete this admin?')) {
        document.getElementById('delete-form-' + adminId).submit();
    }
}
</script>
@endpush