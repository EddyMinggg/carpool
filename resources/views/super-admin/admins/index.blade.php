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

    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Admin Management</h2>
            <a href="{{ route('super-admin.admins.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Create New Admin
            </a>
        </div>
    </div>
    
    <!-- Stats Summary -->
    <div style="display: flex; gap: 4rem; margin-bottom: 1.5rem;">
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

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <!-- Role Filter -->
            <div class="mb-4">
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
    
    // 初始化 DataTable
    table = $('#adminsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[0, 'asc']], // 按 ID 排序
        columnDefs: [
            {
                targets: [5], // Actions column
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
});

function deleteAdmin(adminId) {
    if (confirm('Are you sure you want to delete this admin?')) {
        document.getElementById('delete-form-' + adminId).submit();
    }
}
</script>
@endpush