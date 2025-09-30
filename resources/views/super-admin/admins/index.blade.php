@extends('admin.layout')

@section('page-title', 'Admin Management')

@push('head-styles')
    @if($isMobile)
        <style>
            /* 移動版全局重置 - 最高優先級 */
            * {
                box-sizing: border-box !important;
            }
            
            html, body {
                overflow-x: hidden !important;
                width: 100vw !important;
                max-width: 100vw !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            /* 確保所有容器都不會超出螢幕寬度 */
            div, section, article, main, header, footer, aside, nav {
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            
            /* 強制重置 flex 容器 */
            .flex {
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            
            /* 強制重置主內容區域 */
            .main-content, .page-content {
                width: 100vw !important;
                max-width: 100vw !important;
                padding: 0 !important;
                margin: 0 !important;
                overflow-x: hidden !important;
            }
        </style>
    @endif
@endpush

@section('content')
    {{-- 桌面版樣式 --}}
    @if(!$isMobile)
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
            .dt-button:nth-child(1) i { color: #8b5cf6 !important; }
            .dt-button:nth-child(2) i { color: #10b981 !important; }
            .dt-button:nth-child(3) i { color: #059669 !important; }
            .dt-button:nth-child(4) i { color: #ef4444 !important; }
            .dt-button:nth-child(5) i { color: #6366f1 !important; }
            
            /* hover 時圖標變白色 */
            .dt-button:hover i {
                color: white !important;
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
            
            .dataTables_filter {
                margin-bottom: 1rem !important;
            }
            
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_processing,
            .dataTables_wrapper .dataTables_paginate {
                color: #374151;
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
                border: 2px solid rgba(255, 255, 255, 0.1);
            }
            
            .stats-card-red {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                color: white;
                border-radius: 0.75rem;
                padding: 1.5rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                border: 2px solid rgba(255, 255, 255, 0.1);
            }
            
            .stats-icon-bg {
                background-color: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                padding: 1rem;
            }
            
            /* 桌面版統計卡片額外間距 */
            .desktop-stats .flex {
                gap: 2.5rem;
            }
        </style>
    @endif
    
    {{-- 移動版樣式 --}}
    @if($isMobile)
        <style>
            /* 移動版全局重置 - 覆蓋所有可能的樣式衝突 */
            html, body, #app, .flex, .main-content, .page-content, .mobile-container {
                overflow-x: hidden !important;
                width: 100vw !important;
                max-width: 100vw !important;
                box-sizing: border-box !important;
            }
            
            /* 強制重置所有可能造成水平滾動的元素 */
            * {
                box-sizing: border-box !important;
                max-width: 100vw !important;
            }
            
            /* 特別針對 Tailwind CSS 的重置 */
            .container, .container-fluid, .w-full, .w-screen {
                width: 100vw !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            
            /* 移動版容器 - 絕對防止溢出 */
            .mobile-container {
                width: 100vw !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
                padding: 0 !important;
                margin: 0 !important;
                position: relative !important;
            }
            
            .mobile-container * {
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            
            /* 在移動版中隱藏桌面版元素 */
            .admin-header,
            .desktop-stats,
            .bg-white.rounded-lg.shadow-md,
            #role-filter-desktop,
            #adminsTable,
            .dataTables_wrapper {
                display: none !important;
            }
            
            /* 移動版內容區域 - 嚴格控制寬度 */
            .mobile-content-area {
                padding: 16px !important;
                width: calc(100vw - 32px) !important;
                max-width: calc(100vw - 32px) !important;
                margin: 0 16px !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
            }
            
            /* 搜索和篩選區域 - 防止溢出 */
            .mobile-search-area {
                display: flex !important;
                gap: 12px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            
            .mobile-search-area > div:first-child {
                flex: 1 !important;
                min-width: 0 !important;
                max-width: calc(100% - 96px - 12px) !important;
            }
            
            .mobile-search-area > div:last-child {
                width: 96px !important;
                max-width: 96px !important;
                flex-shrink: 0 !important;
            }
            
            /* 輸入框和選擇框 - 絕對不能超出容器 */
            #mobile-search, #mobile-filter {
                
                max-width: 100% !important;
                box-sizing: border-box !important;
                border: 1px solid #d1d5db !important;
                font-size: 14px !important;
            }
            
            /* 統計卡片網格 - 嚴格控制 */
            .mobile-stats-grid {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 16px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            
            .mobile-stats-card {
                min-width: 0 !important;
                overflow: hidden !important;
                word-wrap: break-word !important;
                max-width: 100% !important;
            }
            
            /* 管理員行 - 完全控制寬度 */
            .mobile-admin-row {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
                word-wrap: break-word !important;
                cursor: pointer !important;
                transition: all 0.2s ease !important;
            }
            
            .mobile-admin-row:hover {
                transform: translateY(-2px) !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
                background: #f8fafc !important;
            }
            
            .mobile-admin-row:active {
                transform: translateY(0) !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
            }
            
            /* Flex 容器內的元素控制 */
            .mobile-admin-row .flex {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            
            .mobile-admin-row .flex > * {
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            
            /* 用戶信息區域 */
            .mobile-admin-row .flex-1 {
                min-width: 0 !important;
                max-width: calc(100% - 40px - 120px - 24px) !important;
                overflow: hidden !important;
            }
            
            /* 操作按鈕區域 */
            .mobile-admin-row .flex-shrink-0:last-child {
                width: 120px !important;
                max-width: 120px !important;
                flex-shrink: 0 !important;
            }
            
            /* 確保觸摸友好的按鈕大小 */
            .touch-target {
                min-height: 44px !important;
                min-width: 44px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            
            /* 文字截斷 */
            .truncate {
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
                max-width: 100% !important;
            }
            
            /* 平滑的過渡效果 */
            .mobile-admin-row {
                transition: transform 0.2s ease, box-shadow 0.2s ease !important;
            }
            
            .mobile-admin-row:hover {
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
            }
            
            /* 卡片點擊反饋 */
            .mobile-admin-row:active {
                transform: scale(0.98) !important;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            }
            
            /* 自定義搜索框樣式 */
            #mobile-search:focus {
                outline: none !important;
                box-shadow: 0 0 0 2px #3b82f6 !important;
                border-color: transparent !important;
            }
            /* 頭像樣式 */
            .avatar-super {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 14px;
                flex-shrink: 0;
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            }
            
            .avatar-admin {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 14px;
                flex-shrink: 0;
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            }
            
            /* 角色標籤樣式 */
            .role-badge {
                padding: 2px 6px;
                border-radius: 10px;
                font-size: 11px;
                font-weight: 500;
                flex-shrink: 0;
                min-width: 0;
                text-align: center;
                white-space: nowrap;
                line-height: 1.2;
            }
            
            .role-super {
                background: #fef2f2;
                color: #dc2626;
            }
            
            .role-admin {
                background: #eff6ff;
                color: #2563eb;
            }
            
            /* 確保所有文字元素都不會造成溢出 */
            h1, h2, h3, h4, h5, h6, p, span, div, button, input, select {
                max-width: 100% !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
            
            /* 浮動 + 按鈕樣式 */
            .floating-add-btn {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 56px;
                height: 56px;
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                border: none;
                border-radius: 50%;
                color: white;
                font-size: 24px;
                font-weight: 500;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
                cursor: pointer;
                z-index: 1000;
                text-decoration: none;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                transform: scale(1);
            }
            
            .floating-add-btn:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
                background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            }
            
            .floating-add-btn:active {
                transform: scale(0.95);
                box-shadow: 0 2px 8px rgba(59, 130, 246, 0.6);
            }
            
            /* 按鈕圖標動畫 */
            .floating-add-btn i {
                transition: transform 0.3s ease;
            }
            
            .floating-add-btn:hover i {
                transform: rotate(90deg);
            }
        </style>
    @endif

    {{-- ============ 桌面版內容 ============ --}}
    @if(!$isMobile)
        <div class="mb-6 admin-header">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Admin Management</h1>
            </div>
        </div>
        
        <!-- 桌面版統計卡片 -->
        <div class="mb-6">
            <div class="desktop-stats">
                <div class="flex gap-8">
                    <div class="stats-card-red flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white text-opacity-80 text-sm font-medium mb-1">Super Admins</p>
                                <p class="text-3xl font-bold" id="super-admins-desktop">
                                    {{ $admins->where('user_role', 'super_admin')->count() }}
                                </p>
                            </div>
                            <div class="stats-icon-bg">
                                <i class="fas fa-crown text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-card-blue flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white text-opacity-80 text-sm font-medium mb-1">Admins</p>
                                <p class="text-3xl font-bold" id="regular-admins-desktop">
                                    {{ $admins->where('user_role', 'admin')->count() }}
                                </p>
                            </div>
                            <div class="stats-icon-bg">
                                <i class="fas fa-user-shield text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 桌面版表格區域 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <!-- 桌面版頂部控制區 -->
                <div class="flex justify-between items-center mb-4">
                    <div class="flex-1">
                        <label for="role-filter-desktop" class="block text-sm font-medium text-gray-700 mb-2">Filter by Role:</label>
                        <select id="role-filter-desktop" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Roles</option>
                            <option value="Admin">Administrators</option>
                            <option value="Super Admin">Super Administrators</option>
                        </select>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('super-admin.admins.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>Create New Admin
                        </a>
                    </div>
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
                                        <a href="{{ route('super-admin.admins.show', ['admin' => $admin->id]) }}" 
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
                                            <button onclick="deleteAdmin({{ $admin->id }}, {{ json_encode($admin->username) }})" 
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
                
                <!-- 桌面版分頁 -->
                @if($admins->hasPages())
                    <div class="mt-6">
                        {{ $admins->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ============ 移動版內容 ============ --}}
    @if($isMobile)
        <div style="width: 100vw; max-width: 100vw; overflow-x: hidden; padding: 0; margin: 0; box-sizing: border-box;">
            <!-- 頂部導航 -->
            <div style="background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100vw; max-width: 100vw; overflow: hidden;">
                <div style="padding: 16px; box-sizing: border-box;">
                    
                    <!-- 搜索和篩選 -->
                    <div style="display: flex; gap: 12px;">
                        <input type="text" 
                               id="mobile-search" 
                               placeholder="Search admins..." 
                               style="flex: 1; min-width: 0; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 16px; box-sizing: border-box; background: white; outline: none;">
                        <select id="mobile-filter" 
                                style="width: 80px; flex-shrink: 0; padding: 12px 8px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; background: white; box-sizing: border-box; outline: none;">
                            <option value="">All</option>
                            <option value="Super Admin">Super</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 統計卡片 -->
            <div style="background: #f9fafb; width: 100vw; max-width: 100vw; overflow: hidden;">
                <div style="padding: 16px; box-sizing: border-box;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <!-- Super Admins 卡片 -->
                        <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 12px; padding: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-width: 0; overflow: hidden; box-sizing: border-box;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="min-width: 0; flex: 1;">
                                    <p style="color: rgba(255,255,255,0.8); font-size: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Super Admins</p>
                                    <p style="font-size: 20px; font-weight: bold; margin: 0;" id="super-admins-mobile">
                                        {{ $admins->where('user_role', 'super_admin')->count() }}
                                    </p>
                                </div>
                                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; padding: 8px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-crown" style="font-size: 12px;"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Admins 卡片 -->
                        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; padding: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-width: 0; overflow: hidden; box-sizing: border-box;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="min-width: 0; flex: 1;">
                                    <p style="color: rgba(255,255,255,0.8); font-size: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Admins</p>
                                    <p style="font-size: 20px; font-weight: bold; margin: 0;" id="regular-admins-mobile">
                                        {{ $admins->where('user_role', 'admin')->count() }}
                                    </p>
                                </div>
                                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; padding: 8px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-user-shield" style="font-size: 12px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 管理員列表 -->
            <div style="padding: 16px; box-sizing: border-box;">
                @if($admins->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($admins as $admin)
                            <a href="{{ route('super-admin.admins.show', ['admin' => $admin->id]) }}" 
                               class="mobile-admin-row" 
                               data-role="{{ $admin->getRoleName() }}" 
                               data-username="{{ strtolower($admin->username) }}" 
                               data-email="{{ strtolower($admin->email) }}"
                               style="background: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #f3f4f6; box-sizing: border-box; overflow: hidden; text-decoration: none; display: block; transition: all 0.2s ease;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <!-- 頭像 -->
                                    <div class="{{ $admin->isSuperAdmin() ? 'avatar-super' : 'avatar-admin' }}">
                                        {{ strtoupper(substr($admin->username, 0, 1)) }}
                                    </div>
                                    
                                    <!-- 用戶資訊 -->
                                    <div style="flex: 1; min-width: 0; overflow: hidden;">
                                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                            <h3 style="font-weight: 600; color: #1f2937; font-size: 16px; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $admin->username }}</h3>
                                            <span class="role-badge {{ $admin->isSuperAdmin() ? 'role-super' : 'role-admin' }}" 
                                                  style="display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-left: 6px;
                                                         {{ $admin->isSuperAdmin() ? 'background: #dc2626; color: white;' : 'background: #2563eb; color: white;' }}">
                                                {{ $admin->isSuperAdmin() ? 'SUPER' : 'ADMIN' }}
                                            </span>
                                        </div>
                                        <p style="font-size: 14px; color: #6b7280; margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $admin->email }}</p>
                                        @if($admin->phone)
                                            <p style="font-size: 12px; color: #9ca3af; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $admin->phone }}</p>
                                        @endif
                                    </div>
                                    
                                    <!-- 箭頭圖標 -->
                                    <div style="color: #d1d5db; flex-shrink: 0;">
                                        <i class="fas fa-chevron-right" style="font-size: 16px;"></i>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <!-- 空狀態 -->
                    <div style="text-align: center; padding: 48px 16px;">
                        <div style="color: #d1d5db; margin-bottom: 16px;">
                            <i class="fas fa-users" style="font-size: 48px;"></i>
                        </div>
                        <h3 style="font-size: 18px; font-weight: 600; color: #4b5563; margin-bottom: 8px;">No Admins Found</h3>
                        <p style="color: #6b7280; margin-bottom: 24px; font-size: 14px;">There are currently no administrator accounts.</p>
                        <a href="{{ route('super-admin.admins.create') }}" 
                           style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-plus"></i>
                            <span>Add First Admin</span>
                        </a>
                    </div>
                @endif
                
                <!-- 移動版分頁 -->
                @if($admins->hasPages())
                    <div style="padding: 16px 0;">
                        {{ $admins->links('pagination::mobile') }}
                    </div>
                @endif
            </div>
        </div>
        
        <!-- 浮動 + 按鈕 -->
        <a href="{{ route('super-admin.admins.create') }}" class="floating-add-btn" title="Add Admin">
            <i class="fas fa-plus"></i>
        </a>
    @endif

    {{-- Delete Confirmation Modal --}}
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
                    Are you sure you want to delete this admin? This action cannot be undone and will permanently remove the admin account.
                </p>
                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px; margin-bottom: 16px;">
                    <p style="margin: 0; font-size: 14px; color: #dc2626;">
                        <i class="fas fa-info-circle" style="margin-right: 6px;"></i>
                        Admin: <strong id="adminToDelete"></strong>
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
                    Delete Admin
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile-specific modal styles --}}
    <style>
        @media (max-width: 640px) {
            #deleteModal > div {
                width: 95% !important;
                margin: 10px !important;
                max-height: 90vh !important;
            }
            
            #deleteModal .modal-footer {
                flex-direction: column !important;
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
        
        #deleteModal > div {
            animation: slideUp 0.2s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
// ========== Modal 功能 ==========
let currentAdminId = null;
let currentAdminName = null;

// 顯示刪除確認 modal
function deleteAdmin(adminId, adminName) {
    currentAdminId = adminId;
    currentAdminName = adminName || `Admin ID: ${adminId}`;
    
    // 設置要刪除的管理員信息
    document.getElementById('adminToDelete').textContent = currentAdminName;
    
    // 顯示 modal
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'flex';
    
    // 防止背景滾動
    document.body.style.overflow = 'hidden';
}

// 關閉 modal
function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
    currentAdminId = null;
    currentAdminName = null;
}

// 確認刪除
function confirmDelete() {
    if (!currentAdminId) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/super-admin/admins/${currentAdminId}`;
    form.style.display = 'none';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    
    form.appendChild(csrfToken);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}

// ESC 鍵關閉 modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>

{{-- 桌面版 JavaScript --}}
@if(!$isMobile)
<script>
$(document).ready(function() {
    console.log('=== Admin Table JavaScript Loading ===');
    console.log('jQuery loaded:', typeof $ !== 'undefined');
    console.log('DataTables loaded:', typeof $.fn.DataTable !== 'undefined');
    
    var table; // 桌面版 DataTable
    
    // 桌面版 DataTable 初始化
    function initDesktopTable() {
        console.log('Initializing desktop table...');
        
        // 檢查表格是否存在
        if ($('#adminsTable').length === 0) {
            console.error('Table #adminsTable not found');
            return;
        }
        
        // 確保 DataTable 插件可用
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTable plugin not available');
            return;
        }
        
        // 檢查是否已經初始化，如果是則先銷毀
        if ($.fn.DataTable.isDataTable('#adminsTable')) {
            console.log('DataTable already exists, destroying...');
            $('#adminsTable').DataTable().destroy();
        }
        
        try {
            table = $('#adminsTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[0, 'asc']],
                columnDefs: [
                    {
                        targets: [5], // Actions column
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: 'Bfrtlip', // 桌面版顯示匯出按鈕
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
                emptyTable: "No administrators found",
                zeroRecords: "No admins match your search criteria",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
                drawCallback: function(settings) {
                    updateDesktopStats();
                }
            });
            
            console.log('DataTable initialized successfully');
            
            // 桌面版角色過濾器
            $('#role-filter-desktop').on('change', function() {
                var selectedRole = $(this).val();
                if (selectedRole === '') {
                    table.search('').columns().search('').draw();
                } else {
                    table.column(4).search(selectedRole, false, false).draw();
                }
            });
            
            updateDesktopStats();
        } catch (error) {
            console.error('DataTable initialization failed:', error);
        }
    }    // 桌面版統計更新
    function updateDesktopStats() {
        if (!table) return;
        
        try {
            var visibleRows = table.rows({search: 'applied'});
            var superAdmins = 0;
            var regularAdmins = 0;
            
            visibleRows.every(function(rowIdx, tableLoop, rowLoop) {
                var data = this.data();
                var roleHtml = data[4];
                var roleText = $(roleHtml).text().trim();
                
                if (roleText === 'Super Admin') {
                    superAdmins++;
                } else if (roleText === 'Admin') {
                    regularAdmins++;
                }
            });
            
            $('#super-admins-desktop').text(superAdmins);
            $('#regular-admins-desktop').text(regularAdmins);
        } catch (error) {
            console.error('Error updating desktop stats:', error);
        }
    }
    
    // 只在 DataTables 準備好且表格還未初始化時才初始化
    if (typeof $.fn.DataTable !== 'undefined') {
        console.log('DataTables ready, initializing immediately');
        initDesktopTable();
    } else {
        // 監聽 DataTables 準備事件
        $(document).on('dataTablesReady', function() {
            console.log('DataTables ready event received');
            initDesktopTable();
        });
    }
    
    // 頁面卸載時清理 DataTable
    $(window).on('beforeunload', function() {
        if (table && $.fn.DataTable.isDataTable('#adminsTable')) {
            table.destroy();
        }
    });
});
</script>
@endif

{{-- 移動版 JavaScript --}}
@if($isMobile)
<script>
// 移動版搜索功能
document.getElementById('mobile-search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    filterMobileAdmins();
});

// 移動版篩選功能
document.getElementById('mobile-filter').addEventListener('change', function(e) {
    filterMobileAdmins();
});

// 統一的篩選函數
function filterMobileAdmins() {
    const searchTerm = document.getElementById('mobile-search').value.toLowerCase();
    const filterRole = document.getElementById('mobile-filter').value;
    const rows = document.querySelectorAll('.mobile-admin-row');
    
    let visibleSuperAdmins = 0;
    let visibleRegularAdmins = 0;
    
    rows.forEach(row => {
        const role = row.getAttribute('data-role');
        const username = row.getAttribute('data-username');
        const email = row.getAttribute('data-email');
        
        const matchesSearch = username.includes(searchTerm) || email.includes(searchTerm);
        const matchesFilter = filterRole === '' || role === filterRole;
        
        if (matchesSearch && matchesFilter) {
            row.style.display = '';
            if (role === 'Super Admin') {
                visibleSuperAdmins++;
            } else if (role === 'Admin') {
                visibleRegularAdmins++;
            }
        } else {
            row.style.display = 'none';
        }
    });
    
    // 更新統計
    document.getElementById('super-admins-mobile').textContent = visibleSuperAdmins;
    document.getElementById('regular-admins-mobile').textContent = visibleRegularAdmins;
}

// 浮動按鈕觸摸反饋
document.addEventListener('DOMContentLoaded', function() {
    const floatingBtn = document.querySelector('.floating-add-btn');
    if (floatingBtn) {
        // 觸摸開始
        floatingBtn.addEventListener('touchstart', function(e) {
            this.style.transform = 'scale(0.95)';
        });
        
        // 觸摸結束
        floatingBtn.addEventListener('touchend', function(e) {
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
        });
        
        // 觸摸取消
        floatingBtn.addEventListener('touchcancel', function(e) {
            this.style.transform = 'scale(1)';
        });
    }
    
    // 管理員卡片觸摸反饋
    const adminCards = document.querySelectorAll('.mobile-admin-row');
    adminCards.forEach(card => {
        card.addEventListener('touchstart', function(e) {
            this.style.transform = 'scale(0.98)';
            this.style.opacity = '0.8';
        });
        
        card.addEventListener('touchend', function(e) {
            setTimeout(() => {
                this.style.transform = 'scale(1)';
                this.style.opacity = '1';
            }, 100);
        });
        
        card.addEventListener('touchcancel', function(e) {
            this.style.transform = 'scale(1)';
            this.style.opacity = '1';
        });
    });
});
</script>
@endif

@endpush