@extends('admin.layout')

@section('title', 'Driver Management')
@section('page-title', 'Driver Management')

@section('content')
    {{-- 移動版 CSS 重設和專用樣式 --}}
    @if ($isMobile)
        <style>
            /* 移動版嚴格CSS重設 - 防止水平滾動 */
            * {
                box-sizing: border-box !important;
                max-width: 100vw !important;
            }

            html,
            body {
                overflow-x: hidden !important;
                width: 100vw !important;
                max-width: 100vw !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .container,
            .container-fluid,
            .row,
            .col,
            [class*="col-"] {
                width: 100vw !important;
                max-width: 100vw !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow-x: hidden !important;
            }

            /* 移動版統計卡片樣式 */
            .mobile-stats-grid {
                display: grid !important;
                grid-template-columns: 1fr 1fr 1fr !important;
                gap: 12px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            .mobile-stat-card {
                background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
                color: white;
                border-radius: 12px;
                padding: 12px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                min-width: 0 !important;
                overflow: hidden !important;
                box-sizing: border-box !important;
            }

            .mobile-stat-card.blue {
                --gradient-start: #3b82f6;
                --gradient-end: #2563eb;
            }

            .mobile-stat-card.green {
                --gradient-start: #10b981;
                --gradient-end: #059669;
            }

            .mobile-stat-card.purple {
                --gradient-start: #8b5cf6;
                --gradient-end: #7c3aed;
            }

            /* 移動版用戶卡片樣式 */
            .mobile-user-card {
                background: white !important;
                border-radius: 12px !important;
                padding: 16px !important;
                margin-bottom: 12px !important;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
                border: 1px solid #f3f4f6 !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
                cursor: pointer !important;
                transition: all 0.2s ease !important;
            }

            .mobile-user-card:hover {
                transform: translateY(-2px) !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
                background: #f8fafc !important;
                text-decoration: none !important;
            }

            .mobile-user-card:active {
                transform: translateY(0) !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
            }

            /* 角色標籤樣式 */
            .role-badge {
                display: inline-block;
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 10px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .role-user {
                background: #f3f4f6;
                color: #6b7280;
            }

            .role-admin {
                background: #2563eb;
                color: white;
            }

            .role-super {
                background: #dc2626;
                color: white;
            }

            /* 頭像樣式 */
            .avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                font-weight: 600;
                color: white;
                flex-shrink: 0;
            }

            .avatar-user {
                background: #6b7280;
            }

            .avatar-admin {
                background: #2563eb;
            }

            .avatar-super {
                background: #dc2626;
            }

            /* 確保所有文字元素都不會造成溢出 */
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            p,
            span,
            div,
            button,
            input,
            select {
                max-width: 100% !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
        </style>
    @endif

    <style>
        /* 自定義DataTable樣式 */
        .dataTables_wrapper {
            padding: 0;
        }

        .dataTables_filter {
            float: right;
            margin-bottom: 1rem;
        }

        .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin-left: 0.5rem;
        }

        .dataTables_length {
            margin-top: 1.5rem !important;
            margin-bottom: 1rem !important;
        }

        .dataTables_length select {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.25rem;
            margin: 0 0.5rem;
        }

        .dataTables_info {
            padding-top: 1.5rem !important;
            margin-bottom: 0.5rem !important;
        }

        .dataTables_paginate {
            padding-top: 1rem !important;
        }

        .dataTables_paginate .paginate_button {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            margin: 0 0.125rem;
            text-decoration: none;
        }

        .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6;
        }

        .dataTables_paginate .paginate_button.current {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        #driversTable {
            width: 100% !important;
        }

        #driversTable thead th {
            background-color: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
            padding: 0.75rem;
            font-weight: 500;
            text-align: left;
        }

        #driversTable tbody td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        #driversTable tbody tr:hover {
            background-color: #f9fafb;
        }

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
        .dt-button:nth-child(1) i {
            /* Copy */
            color: #8b5cf6 !important;
        }

        .dt-button:nth-child(2) i {
            /* CSV */
            color: #10b981 !important;
        }

        .dt-button:nth-child(3) i {
            /* Excel */
            color: #059669 !important;
        }

        .dt-button:nth-child(4) i {
            /* PDF */
            color: #ef4444 !important;
        }

        .dt-button:nth-child(5) i {
            /* Print */
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

        /* Action按鈕樣式 */
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

        .stats-card-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .stats-card-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
    {{-- ============ 桌面版內容 ============ --}}
    @if (!$isMobile)
        <!-- Stats Summary (只顯示用戶相關統計) -->
        <div style="display: flex; gap: 2rem; margin-bottom: 1.5rem;">
            <div class="stats-card-blue" style="flex: 1;">
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            style="color: rgba(255, 255, 255, 0.8); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                            Total Users</p>
                        <p style="font-size: 1.875rem; font-weight: bold;" id="total-users">{{ $drivers->count() }}</p>
                    </div>
                    <div class="stats-icon-bg">
                        <i class="fas fa-users" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>

            <div class="stats-card-green" style="flex: 1;">
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            style="color: rgba(255, 255, 255, 0.8); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                            Active Users</p>
                        <p style="font-size: 1.875rem; font-weight: bold;" id="active-users">
                            {{ $drivers->whereNotNull('email_verified_at')->count() }}
                        </p>
                    </div>
                    <div class="stats-icon-bg">
                        <i class="fas fa-user-check" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>

            <div class="stats-card-purple" style="flex: 1;">
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            style="color: rgba(255, 255, 255, 0.8); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                            New This Month</p>
                        <p style="font-size: 1.875rem; font-weight: bold;" id="new-users">
                            {{ $drivers->where('created_at', '>=', now()->startOfMonth())->count() }}
                        </p>
                    </div>
                    <div class="stats-icon-bg">
                        <i class="fas fa-user-plus" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <!-- Search only (Role filter removed since we only show regular users) -->
                <div class="flex justify-between">
                    <div class="mb-4">
                        <label for="search-input" class="block text-sm font-medium text-gray-700 mb-2">Search Users:</label>
                        <input type="text" id="search-input" placeholder="Search by username or email..."
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-full max-w-sm">
                    </div>
                    <div class="flex items-center">
                        <a href="{{ route('admin.drivers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Create New Driver
                        </a>
                    </div>
                </div>

                <table id="driversTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($drivers as $driver)
                            <tr>
                                <td>{{ $driver->id }}</td>
                                <td>{{ $driver->username }}</td>
                                <td>{{ $driver->email }}</td>
                                <td>{{ $driver->phone ?: 'N/A' }}</td>
                                <td>
                                    <div class="flex space-x-1">
                                        <a href="{{ route('admin.drivers.show', $driver->id) }}"
                                            class="action-btn action-btn-blue" title="View Driver">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.drivers.edit', $driver->id) }}"
                                            class="action-btn action-btn-yellow" title="Edit Driver">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        @if (Auth::user()->id !== $driver->id && !(Auth::user()->user_role === 'admin' && $driver->user_role === 'super_admin'))
                                            <button
                                                onclick="showDeleteModal(document.getElementById('delete-form-{{ $driver->id }}'), '{{ $driver->username }}')"
                                                class="action-btn action-btn-red" title="Delete Driver">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $driver->id }}"
                                                action="{{ route('admin.users.destroy', $driver->id) }}" method="POST"
                                                style="display: none;">
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
    @endif

    {{-- ============ 移動版內容 ============ --}}
    @if ($isMobile)
        <div style="width: 100vw; max-width: 100vw; overflow-x: hidden; padding: 0; margin: 0; box-sizing: border-box;">
            <!-- 統計卡片 -->
            <div style="background: #f9fafb; width: 100vw; max-width: 100vw; overflow: hidden;">
                <div style="padding: 16px; box-sizing: border-box;">
                    <div class="mobile-stats-grid">
                        <!-- Total Users -->
                        <div class="mobile-stat-card blue">
                            <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                                <i class="fas fa-users" style="font-size: 18px; margin-bottom: 8px;"></i>
                                <p
                                    style="color: rgba(255,255,255,0.8); font-size: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">
                                    Total</p>
                                <p style="font-size: 20px; font-weight: bold; margin: 0;" id="mobile-total-users">
                                    {{ $drivers->count() }}</p>
                            </div>
                        </div>

                        <!-- Active Users -->
                        <div class="mobile-stat-card green">
                            <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                                <i class="fas fa-user-check" style="font-size: 18px; margin-bottom: 8px;"></i>
                                <p
                                    style="color: rgba(255,255,255,0.8); font-size: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">
                                    Active</p>
                                <p style="font-size: 20px; font-weight: bold; margin: 0;" id="mobile-active-users">
                                    {{ $drivers->whereNotNull('email_verified_at')->count() }}</p>
                            </div>
                        </div>

                        <!-- New This Month -->
                        <div class="mobile-stat-card purple">
                            <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                                <i class="fas fa-user-plus" style="font-size: 18px; margin-bottom: 8px;"></i>
                                <p
                                    style="color: rgba(255,255,255,0.8); font-size: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">
                                    New</p>
                                <p style="font-size: 20px; font-weight: bold; margin: 0;" id="mobile-new-users">
                                    {{ $drivers->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 搜索 (移除角色篩選，因為只顯示普通用戶) -->
            <div style="background: white; padding: 16px; box-sizing: border-box;">
                <div style="margin-bottom: 16px;">
                    <input type="text" id="mobile-search" placeholder="Search users..."
                        style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 16px; box-sizing: border-box; background: white; outline: none;">
                </div>
            </div>

            <!-- 用戶列表 -->
            <div style="padding: 16px; box-sizing: border-box;">
                @if ($drivers->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 12px;" id="mobile-users-container">
                        @foreach ($drivers as $driver)
                            <a href="{{ route('admin.users.show', $driver->id) }}" class="mobile-user-card"
                                data-role="driver" data-username="{{ strtolower($driver->username) }}"
                                data-email="{{ strtolower($driver->email) }}"
                                style="text-decoration: none; display: block; transition: all 0.2s ease;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <!-- 頭像 -->
                                    <div
                                        class="avatar {{ $driver->user_role === 'super_admin' ? 'avatar-super' : ($driver->user_role === 'admin' ? 'avatar-admin' : 'avatar-user') }}">
                                        {{ strtoupper(substr($driver->username, 0, 1)) }}
                                    </div>

                                    <!-- 用戶資訊 -->
                                    <div style="flex: 1; min-width: 0; overflow: hidden;">
                                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                                            <h3
                                                style="font-weight: 600; color: #1f2937; font-size: 16px; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-right: 8px;">
                                                {{ $driver->username }}</h3>
                                            <span
                                                class="role-badge {{ $driver->user_role === 2 ? 'super_admin' : ($driver->user_role === 'admin' ? 'role-admin' : 'role-user') }}">
                                                {{ $driver->user_role === 'super_admin' ? 'SUPER' : ($driver->user_role === 'admin' ? 'ADMIN' : 'USER') }}
                                            </span>
                                        </div>
                                        <p
                                            style="font-size: 14px; color: #6b7280; margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $driver->email }}</p>
                                        @if ($driver->phone)
                                            <p
                                                style="font-size: 12px; color: #9ca3af; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $driver->phone }}</p>
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
                        <h3 style="font-size: 18px; font-weight: 600; color: #4b5563; margin-bottom: 8px;">No Users Found
                        </h3>
                        <p style="color: #6b7280; margin-bottom: 24px; font-size: 14px;">There are currently no users in
                            the system.</p>
                    </div>
                @endif
            </div>

            <!-- 移動版分頁 -->
            @if ($drivers instanceof \Illuminate\Pagination\LengthAwarePaginator && $drivers->hasPages())
                <div style="margin-top: 30px; padding-bottom: 20px;">
                    {{ $drivers->links('pagination::mobile') }}
                </div>
            @endif

        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div
            style="background: white; border-radius: 8px; max-width: 400px; width: 90%; margin: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3); max-height: 80vh; overflow-y: auto;">
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
                    Are you sure you want to delete this user? This action cannot be undone and will permanently remove the
                    user account.
                </p>
                <div
                    style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px; margin-bottom: 16px;">
                    <p style="margin: 0; font-size: 14px; color: #dc2626;">
                        <i class="fas fa-info-circle" style="margin-right: 6px;"></i>
                        Driver: <strong id="userToDelete"></strong>
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div
                style="padding: 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; gap: 12px;">
                <button onclick="closeDeleteModal()"
                    style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px; transition: background-color 0.2s; flex: 1;"
                    onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    Cancel
                </button>
                <button onclick="confirmDelete()"
                    style="padding: 12px 24px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px; transition: background-color 0.2s; flex: 1;"
                    onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                    <i class="fas fa-trash" style="margin-right: 6px;"></i>
                    Delete Driver
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
    @if (!$isMobile)
        <script>
            $(document).ready(function() {
                var table; // 在全局作用域聲明

                // 等待 DataTables 加載完成
                function initDataTable() {
                    if (typeof $.fn.DataTable === 'undefined') {
                        console.log('Waiting for DataTables to load...');
                        setTimeout(initDataTable, 100);
                        return;
                    }

                    console.log('DataTables is ready, initializing table...');

                    // 初始化 DataTable
                    table = $('#driversTable').DataTable({
                        responsive: true,
                        pageLength: 10,
                        lengthMenu: [
                            [10, 25, 50, 100, -1],
                            [10, 25, 50, 100, "All"]
                        ],
                        order: [
                            [0, 'asc']
                        ],
                        columnDefs: [{
                            targets: [4], // Actions column
                            orderable: false,
                            searchable: false
                        }],
                        dom: 'Bfrtlip',
                        buttons: [{
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
                            search: "Search users:",
                            lengthMenu: "Show _MENU_ users per page",
                            info: "Showing _START_ to _END_ of _TOTAL_ users",
                            infoEmpty: "No users found",
                            infoFiltered: "(filtered from _MAX_ total users)",
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

                    // 角色過濾器事件 (移除，因為只顯示普通用戶)
                    $('#search-input').on('keyup', function() {
                        if (table) {
                            table.search(this.value).draw();
                        }
                    });

                    // 更新統計數據函數
                    function updateStats() {
                        try {
                            if (!table) return;

                            var info = table.page.info();
                            var totalVisible = info.recordsDisplay;

                            // 因為只顯示普通用戶，所以所有統計都是用戶相關的
                            $('#total-users').text(totalVisible);

                            // 更新其他統計（基於可見的行）
                            var visibleRows = table.rows({
                                search: 'applied'
                            });
                            var activeUsers = 0;
                            var newUsers = 0;

                            // 這裡可以根據需要計算 active 和 new users
                            // 暫時顯示總數
                            $('#active-users').text(totalVisible);
                            $('#new-users').text(totalVisible);
                        } catch (error) {
                            console.error('Error updating stats:', error);
                        }
                    }

                } // 結束 initDataTable 函數

                // 調用初始化函數
                initDataTable();
            });
        </script>
    @else
        <script>
            $(document).ready(function() {
                // 移動版專用腳本

                // 搜索功能（移除角色篩選）
                $('#mobile-search').on('input', function() {
                    var searchTerm = $(this).val().toLowerCase();
                    filterUsers(searchTerm);
                });

                // 篩選用戶函數（只根據搜索詞篩選）
                function filterUsers(searchTerm) {
                    var visibleCount = 0;

                    $('.mobile-user-card').each(function() {
                        var $card = $(this);
                        var username = $card.data('username');
                        var email = $card.data('email');

                        var matchesSearch = searchTerm === '' ||
                            username.includes(searchTerm) ||
                            email.includes(searchTerm);

                        if (matchesSearch) {
                            $card.show();
                            visibleCount++;
                        } else {
                            $card.hide();
                        }
                    });

                    // 更新統計卡片（只顯示可見的用戶數）
                    $('#mobile-total-users').text(visibleCount);
                    $('#mobile-active-users').text(visibleCount); // 簡化統計
                    $('#mobile-new-users').text(visibleCount); // 簡化統計
                }

                // 卡片觸控效果
                $('.mobile-user-card').on('touchstart', function() {
                    $(this).css({
                        'transform': 'scale(0.98)',
                        'opacity': '0.8'
                    });
                }).on('touchend', function() {
                    var self = this;
                    setTimeout(function() {
                        $(self).css({
                            'transform': 'scale(1)',
                            'opacity': '1'
                        });
                    }, 100);
                }).on('touchcancel', function() {
                    $(this).css({
                        'transform': 'scale(1)',
                        'opacity': '1'
                    });
                });

                // 統計卡片點擊效果
                $('.mobile-stat-card').on('touchstart', function() {
                    $(this).css('transform', 'scale(0.98)');
                }).on('touchend', function() {
                    $(this).css('transform', 'scale(1)');
                });

                // 滾動到頂部按鈕
                if ($('.mobile-user-card').length > 8) {
                    $('<button>')
                        .css({
                            'position': 'fixed',
                            'bottom': '20px',
                            'right': '20px',
                            'z-index': '1000',
                            'background': '#3b82f6',
                            'color': 'white',
                            'border': 'none',
                            'border-radius': '50%',
                            'width': '50px',
                            'height': '50px',
                            'box-shadow': '0 4px 8px rgba(0,0,0,0.2)',
                            'display': 'none',
                            'cursor': 'pointer'
                        })
                        .html('<i class="fas fa-chevron-up"></i>')
                        .attr('id', 'mobileScrollToTop')
                        .appendTo('body')
                        .on('click', function() {
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        });

                    $(window).on('scroll', function() {
                        if ($(this).scrollTop() > 300) {
                            $('#mobileScrollToTop').fadeIn();
                        } else {
                            $('#mobileScrollToTop').fadeOut();
                        }
                    });
                }
            });
        </script>
    @endif

    <script>
        let deleteForm = null;
        let userName = '';

        function showDeleteModal(form, name) {
            deleteForm = form;
            userName = name;
            document.getElementById('userToDelete').textContent = userName;

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
            userName = '';
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
@endpush
