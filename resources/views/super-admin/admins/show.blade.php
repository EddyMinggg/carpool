@extends('admin.layout')

@section('page-title', 'Admin Details')

@section('title', 'Admin Details')

@push('head-styles')
    @if($isMobile)
        <style>
            /* 移動版全局重置 - 最高優先級 */
            * {
                box-sizing: border-box !important;
                max-width: 100vw !important;
            }
            
            html, body {
                overflow-x: hidden !important;
                max-width: 100vw !important;
                width: 100vw !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            /* 確保所有容器都不會超出螢幕寬度 */
            div, section, article, main, header, footer, aside, nav {
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            
            /* 移動版容器 - 絕對防止溢出 */
            .mobile-container {
                width: 100vw !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
                padding: 16px !important;
                box-sizing: border-box !important;
            }
            
            /* 詳情卡片樣式 */
            .mobile-detail-card {
                background: white !important;
                border-radius: 8px !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                padding: 20px !important;
                margin-bottom: 16px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            
            /* 信息項目樣式 */
            .mobile-info-item {
                margin-bottom: 16px !important;
                border-bottom: 1px solid #f3f4f6 !important;
                padding-bottom: 12px !important;
            }
            
            .mobile-info-item:last-child {
                border-bottom: none !important;
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
            }
            
            .mobile-info-label {
                display: block !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                color: #6b7280 !important;
                margin-bottom: 4px !important;
            }
            
            .mobile-info-value {
                font-size: 16px !important;
                color: #374151 !important;
                font-weight: 500 !important;
            }
            
            /* 角色標籤樣式 */
            .role-badge {
                display: inline-block !important;
                padding: 4px 8px !important;
                border-radius: 12px !important;
                font-size: 12px !important;
                font-weight: 600 !important;
            }
            
            .role-super {
                background: #fef2f2 !important;
                color: #dc2626 !important;
            }
            
            .role-admin {
                background: #eff6ff !important;
                color: #2563eb !important;
            }
            
            /* 狀態標籤樣式 */
            .status-verified {
                color: #059669 !important;
                background: #d1fae5 !important;
            }
            
            .status-unverified {
                color: #dc2626 !important;
                background: #fef2f2 !important;
            }
            
            /* 按鈕樣式 */
            .mobile-action-btn {
                flex: 1;
                padding: 12px 16px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                text-align: center;
                text-decoration: none;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                min-width: 100px;
                height: 48px;
                line-height: 1;
                white-space: nowrap;
                box-sizing: border-box;
            }

            .mobile-btn-blue {
                background-color: #3b82f6;
                color: white;
            }

            .mobile-btn-blue:hover {
                background-color: #2563eb;
                color: white;
            }

            .mobile-btn-gray {
                background-color: #6b7280;
                color: white;
            }

            .mobile-btn-gray:hover {
                background-color: #4b5563;
                color: white;
            }
            
            .mobile-btn {
                padding: 12px 20px !important;
                border-radius: 6px !important;
                font-weight: 600 !important;
                font-size: 16px !important;
                border: none !important;
                cursor: pointer !important;
                transition: all 0.2s !important;
                margin: 8px 0 !important;
                min-height: 44px !important;
                width: 100% !important;
                box-sizing: border-box !important;
                text-align: center !important;
                text-decoration: none !important;
                display: block !important;
            }
            
            .mobile-btn-primary {
                background: #f59e0b !important;
                color: white !important;
            }
            
            .mobile-btn-primary:hover {
                background: #d97706 !important;
            }
            
            .mobile-btn-secondary {
                background: #f3f4f6 !important;
                color: #374151 !important;
            }
            
            .mobile-btn-secondary:hover {
                background: #e5e7eb !important;
            }
            
            .mobile-btn-danger {
                background: #dc2626 !important;
                color: white !important;
                margin-top: 16px !important;
            }
            
            .mobile-btn-danger:hover {
                background: #b91c1c !important;
            }
            
            /* 隱藏桌面版內容 */
            .desktop-content {
                display: none !important;
            }
        </style>
    @endif
@endpush

@section('content')
    {{-- 桌面版內容 --}}
    @if(!$isMobile)
        <div class="desktop-content">
            <div class="flex justify-between items-center mb-6">
                <div class="flex space-x-2">
                    <a href="{{ route('super-admin.admins.edit', $admin->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('super-admin.admins.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">User Information</h3>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Username</label>
                                <p class="text-gray-900 font-medium">{{ $admin->username }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Email</label>
                                <p class="text-gray-900">{{ $admin->email }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Role</label>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $admin->isSuperAdmin() ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $admin->getRoleName() }}
                                </span>
                            </div>
                        </div>

                        <!-- Account Status -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Account Status</h3>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Phone Verified</label>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $admin->phone_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $admin->phone_verified_at ? 'Verified' : 'Not Verified' }}
                                </span>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Created At</label>
                                <p class="text-gray-900">{{ $admin->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                                <p class="text-gray-900">{{ $admin->updated_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($admin->id !== Auth::user()->id)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Danger Zone</h3>
                            <button onclick="deleteAdmin({{ $admin->id }}, {{ json_encode($admin->username) }})" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete Admin
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- 移動版內容 --}}
    @if($isMobile)
        <div class="mobile-container">
            <!-- 用戶信息卡片 -->
            <div class="mobile-detail-card">
                <h3 style="font-size: 18px; font-weight: 700; color: #374151; margin-bottom: 16px; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px;">User Information</h3>
                
                <div class="mobile-info-item">
                    <span class="mobile-info-label">Username</span>
                    <div class="mobile-info-value">{{ $admin->username }}</div>
                </div>
                
                <div class="mobile-info-item">
                    <span class="mobile-info-label">Email</span>
                    <div class="mobile-info-value">{{ $admin->email }}</div>
                </div>
                
                <div class="mobile-info-item">
                    <span class="mobile-info-label">Role</span>
                    <div class="mobile-info-value">
                        <span class="role-badge {{ $admin->isSuperAdmin() ? 'role-super' : 'role-admin' }}">
                            {{ $admin->getRoleName() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 狀態信息卡片 -->
            <div class="mobile-detail-card">
                <h3 style="font-size: 18px; font-weight: 700; color: #374151; margin-bottom: 16px; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px;">Account Status</h3>
                
                <div class="mobile-info-item">
                    <span class="mobile-info-label">Phone Verified</span>
                    <div class="mobile-info-value">
                        <span class="role-badge {{ $admin->phone_verified_at ? 'status-verified' : 'status-unverified' }}">
                            {{ $admin->phone_verified_at ? 'Verified' : 'Not Verified' }}
                        </span>
                    </div>
                </div>
                
                <div class="mobile-info-item">
                    <span class="mobile-info-label">Created At</span>
                    <div class="mobile-info-value">{{ $admin->created_at->format('Y-m-d H:i:s') }}</div>
                </div>
                
                <div class="mobile-info-item">
                    <span class="mobile-info-label">Last Updated</span>
                    <div class="mobile-info-value">{{ $admin->updated_at->format('Y-m-d H:i:s') }}</div>
                </div>
            </div>

            <!-- 操作按鈕 -->
            <div class="mobile-detail-card">
                <h3 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px;">
                    Actions
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <!-- Edit and Back buttons in same row -->
                    <div style="display: flex; gap: 12px;">
                        <a href="{{ route('super-admin.admins.edit', $admin->id) }}" class="mobile-action-btn mobile-btn-blue" style="flex: 1;">
                            <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                            </svg>
                            Edit
                        </a>
                        
                        <a href="{{ route('super-admin.admins.index') }}" class="mobile-action-btn mobile-btn-gray" style="flex: 1;">
                            <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Back
                        </a>
                    </div>
                    
                    <!-- Delete Button -->
                    @if($admin->id !== Auth::user()->id)
                        <button onclick="deleteAdmin({{ $admin->id }}, {{ json_encode($admin->username) }})" class="mobile-action-btn" style="background: #ef4444 !important; color: white !important;">
                            <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Delete
                        </button>
                    @endif
                </div>
            </div>
        </div>
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
@endpush