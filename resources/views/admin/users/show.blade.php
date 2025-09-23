@extends('admin.layout')

@section('title', 'User Management - Details')
@section('page-title', 'User Details')

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
            
            /* 移動版卡片樣式 */
            .mobile-info-card {
                background: white !important;
                border-radius: 12px !important;
                padding: 20px !important;
                margin: 16px !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                border: 1px solid #f3f4f6 !important;
                width: calc(100vw - 32px) !important;
                max-width: calc(100vw - 32px) !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
            }
            
            /* 角色標籤樣式 */
            .role-badge {
                display: inline-block;
                padding: 6px 12px;
                border-radius: 16px;
                font-size: 12px;
                font-weight: 700;
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
            .avatar-large {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                font-weight: 700;
                color: white;
                flex-shrink: 0;
                margin: 0 auto 16px auto;
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
            
            /* 移動版按鈕樣式 */
            .mobile-action-btn {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 12px 16px !important;
                border-radius: 8px !important;
                font-weight: 600 !important;
                text-decoration: none !important;
                font-size: 14px !important;
                min-height: 44px !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                transition: all 0.2s !important;
                gap: 6px !important;
                border: none !important;
                cursor: pointer !important;
            }
            
            .mobile-btn-blue {
                background: #3b82f6 !important;
                color: white !important;
            }
            
            .mobile-btn-gray {
                background: #6b7280 !important;
                color: white !important;
            }
            
            /* 確保所有文字元素都不會造成溢出 */
            h1, h2, h3, h4, h5, h6, p, span, div, button, input, select {
                max-width: 100% !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
        </style>
    @endif

    {{-- ============ 桌面版內容 ============ --}}
    @if(!$isMobile)
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">User Details #{{ $user->id }}</h2>
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-900 text-sm">
                ← Back to User List
            </a>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 py-2">
                <div class="py-3">
                    <p class="text-sm text-gray-500">Username</p>
                    <p class="text-gray-900">{{ $user->username }}</p>
                </div>
                <div class="py-3">
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>
                <div class="py-3">
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="text-gray-900">{{ $user->phone }}</p>
                </div>
                <div class="py-3">
                    <p class="text-sm text-gray-500">Role</p>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $user->is_admin === 2 ? 'bg-red-100 text-red-800' : ($user->is_admin === 1 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ $user->is_admin === 2 ? 'Super Admin' : ($user->is_admin === 1 ? 'Admin' : 'User') }}
                    </span>
                </div>
                <div class="py-3">
                    <p class="text-sm text-gray-500">Registered At</p>
                    <p class="text-gray-900">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Actions</h3>
            <div class="flex space-x-4">
                @if(!(Auth::user()->is_admin === 1 && $user->is_admin === 2))
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
                @endif
                
                @if(Auth::user()->id !== $user->id && !(Auth::user()->is_admin === 1 && $user->is_admin === 2))
                    <button onclick="showDeleteModal(document.getElementById('deleteForm'), '{{ $user->username }}')" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
                    
                    <form id="deleteForm" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
                
                @if(Auth::user()->is_admin === 1 && $user->is_admin === 2)
                    <p class="text-gray-500 italic">Super Admin can only be managed by another Super Admin</p>
                @endif
            </div>
        </div>
    @endif

    {{-- ============ 移動版內容 ============ --}}
    @if($isMobile)
        {{-- 用戶頭像和基本信息卡片 --}}
        <div class="mobile-info-card">
            {{-- 頭像 --}}
            <div style="text-align: center; margin-bottom: 20px;">
                <div class="avatar-large {{ $user->is_admin === 2 ? 'avatar-super' : ($user->is_admin === 1 ? 'avatar-admin' : 'avatar-user') }}">
                    {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
                </div>
                
                {{-- 用戶名和角色 --}}
                <div style="text-align: center;">
                    <h2 style="font-size: 24px; font-weight: 700; color: #1f2937; margin: 0 0 8px 0; max-width: 100%; word-wrap: break-word;">
                        {{ $user->username }}
                    </h2>
                    <span class="role-badge {{ $user->is_admin === 2 ? 'role-super' : ($user->is_admin === 1 ? 'role-admin' : 'role-user') }}">
                        {{ $user->is_admin === 2 ? 'SUPER ADMIN' : ($user->is_admin === 1 ? 'ADMIN' : 'USER') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- 基本信息卡片 --}}
        <div class="mobile-info-card">
            <h3 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px;">
                Basic Information
            </h3>
            
            {{-- 郵箱 --}}
            <div style="margin-bottom: 16px;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 4px; font-weight: 600;">Email</div>
                <div style="font-size: 16px; color: #1f2937; word-wrap: break-word; max-width: 100%;">
                    {{ strlen($user->email) > 25 ? substr($user->email, 0, 25) . '...' : $user->email }}
                </div>
            </div>
            
            {{-- 電話 --}}
            <div style="margin-bottom: 16px;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 4px; font-weight: 600;">Phone</div>
                <div style="font-size: 16px; color: #1f2937; word-wrap: break-word;">
                    {{ $user->phone ?: 'Not Set' }}
                </div>
            </div>
            
            {{-- 註冊時間 --}}
            <div>
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 4px; font-weight: 600;">Registered At</div>
                <div style="font-size: 16px; color: #1f2937;">
                    {{ $user->created_at->format('Y-m-d H:i') }}
                </div>
            </div>
        </div>

        {{-- 操作按鈕卡片 --}}
        <div class="mobile-info-card">
            <h3 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px;">
                Actions
            </h3>
            
            @if(Auth::user()->is_admin === 1 && $user->is_admin === 2)
                <div style="text-align: center; padding: 20px; color: #6b7280; font-style: italic;">
                    Super Admin can only be managed by another Super Admin
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    {{-- 編輯和返回按鈕同一行 --}}
                    <div style="display: flex; gap: 12px;">
                        @if(!(Auth::user()->is_admin === 1 && $user->is_admin === 2))
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="mobile-action-btn mobile-btn-blue" style="flex: 1;">
                                <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                                Edit
                            </a>
                        @endif
                        
                        <a href="{{ route('admin.users.index') }}" class="mobile-action-btn mobile-btn-gray" style="flex: 1;">
                            <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Back
                        </a>
                    </div>
                    
                    {{-- 刪除按鈕 --}}
                    @if(Auth::user()->id !== $user->id && !(Auth::user()->is_admin === 1 && $user->is_admin === 2))
                        <button onclick="showMobileDeleteModal('{{ $user->username }}')" class="mobile-action-btn" style="background: #ef4444 !important; color: white !important;">
                            <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Delete
                        </button>
                    @endif
                </div>
            @endif
        </div>

        {{-- 移動版 JavaScript --}}
        <script>
            // 移動版觸控反饋
            document.querySelectorAll('.mobile-action-btn').forEach(btn => {
                btn.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.95)';
                    this.style.opacity = '0.8';
                });
                
                btn.addEventListener('touchend', function() {
                    this.style.transform = 'scale(1)';
                    this.style.opacity = '1';
                });
            });
        </script>
        
        {{-- 手機版刪除確認模態框 --}}
        <div id="mobileDeleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 8px; max-width: 90%; width: 90%; margin: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3); max-height: 80vh; overflow-y: auto;">
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
                        Are you sure you want to delete this user? This action cannot be undone and will permanently remove the user account.
                    </p>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px; margin-bottom: 16px;">
                        <p style="margin: 0; font-size: 14px; color: #dc2626;">
                            <i class="fas fa-info-circle" style="margin-right: 6px;"></i>
                            User: <strong id="mobileUserToDelete"></strong>
                        </p>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div style="padding: 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; gap: 12px;">
                    <button onclick="closeMobileDeleteModal()" 
                            style="padding: 12px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px; transition: background-color 0.2s; flex: 1;"
                            ontouchstart="this.style.background='#e5e7eb'" ontouchend="this.style.background='#f3f4f6'">
                        Cancel
                    </button>
                    <button onclick="confirmMobileDelete()" 
                            style="padding: 12px 24px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 16px; transition: background-color 0.2s; flex: 1;"
                            ontouchstart="this.style.background='#b91c1c'" ontouchend="this.style.background='#dc2626'">
                        <i class="fas fa-trash" style="margin-right: 6px;"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>
        
        {{-- 手機版隱藏的刪除表單 --}}
        <form id="mobileDeleteForm" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        
        <script>
            let mobileUserName = '';

            function showMobileDeleteModal(name) {
                mobileUserName = name;
                document.getElementById('mobileUserToDelete').textContent = mobileUserName;
                
                const modal = document.getElementById('mobileDeleteModal');
                modal.style.display = 'flex';
            }

            function closeMobileDeleteModal() {
                document.getElementById('mobileDeleteModal').style.display = 'none';
            }

            function confirmMobileDelete() {
                document.getElementById('mobileDeleteForm').submit();
            }
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
                        Are you sure you want to delete this user? This action cannot be undone and will permanently remove the user account.
                    </p>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px; margin-bottom: 16px;">
                        <p style="margin: 0; font-size: 14px; color: #dc2626;">
                            <i class="fas fa-info-circle" style="margin-right: 6px;"></i>
                            User: <strong id="userToDelete"></strong>
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
    @endif
@endsection
