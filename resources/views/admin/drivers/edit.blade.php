@extends('admin.layout')

@section('title', 'Driver Management - Edit')
@section('page-title', 'Edit Driver')

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
            
            /* 移動版表單樣式 */
            .mobile-form-card {
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
            
            /* 表單輸入樣式 */
            .mobile-input {
                width: 100% !important;
                padding: 16px !important;
                border: 2px solid #e5e7eb !important;
                border-radius: 12px !important;
                font-size: 16px !important;
                box-sizing: border-box !important;
                background: #fafafa !important;
                transition: all 0.2s !important;
            }
            
            .mobile-input:focus {
                border-color: #3b82f6 !important;
                background: white !important;
                outline: none !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            }
            
            .mobile-label {
                display: block !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                color: #374151 !important;
                margin-bottom: 8px !important;
            }
            
            .mobile-select {
                width: 100% !important;
                padding: 16px !important;
                border: 2px solid #e5e7eb !important;
                border-radius: 12px !important;
                font-size: 16px !important;
                box-sizing: border-box !important;
                background: #fafafa !important;
                appearance: none !important;
                background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
                background-repeat: no-repeat !important;
                background-position: right 12px center !important;
                background-size: 20px !important;
            }
            
            .mobile-checkbox-wrapper {
                display: flex !important;
                align-items: center !important;
                padding: 16px !important;
                background: #fafafa !important;
                border: 2px solid #e5e7eb !important;
                border-radius: 12px !important;
                gap: 12px !important;
            }
            
            .mobile-checkbox {
                width: 20px !important;
                height: 20px !important;
                accent-color: #3b82f6 !important;
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
                border: none !important;
                cursor: pointer !important;
                gap: 6px !important;
            }
            
            .mobile-btn-blue {
                background: #3b82f6 !important;
                color: white !important;
            }
            
            .mobile-btn-gray {
                background: #6b7280 !important;
                color: white !important;
            }
            
            .mobile-btn-red {
                background: #ef4444 !important;
                color: white !important;
            }
            
            .error-text {
                color: #ef4444 !important;
                font-size: 14px !important;
                margin-top: 4px !important;
            }
        </style>
    @endif

    {{-- ============ 桌面版內容 ============ --}}
    @if(!$isMobile)
        <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
            <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" id="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('username', $driver->username) }}">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('email', $driver->email) }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" name="phone" id="phone" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        value="{{ old('phone', $driver->phone) }}"
                        placeholder="e.g., +852 1234 5678">
                    <p class="mt-1 text-xs text-gray-500">Optional: Include country code for international numbers</p>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <!-- Driver 角色固定，無需修改 -->
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                        Driver (Fixed)
                    </div>
                    <input type="hidden" name="user_role" value="driver">
                    <p class="mt-1 text-sm text-gray-500">Driver role cannot be changed in this management section.</p>
                </div>
                <div class="flex space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Update Driver</button>
                    <a href="{{ route('admin.drivers.show', $driver->id) }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    @endif

    {{-- ============ 移動版內容 ============ --}}
    @if($isMobile)
        {{-- 表單卡片 --}}
        <div class="mobile-form-card">
            <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST" id="editUserForm">
                @csrf
                @method('PUT')
                
                {{-- 用戶名 --}}
                <div style="margin-bottom: 20px;">
                    <label for="username" class="mobile-label">Username</label>
                    <input type="text" 
                           name="username" 
                           id="username" 
                           class="mobile-input"
                           value="{{ old('username', $driver->username) }}"
                           placeholder="Enter username">
                    @error('username')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- 郵箱 --}}
                <div style="margin-bottom: 20px;">
                    <label for="email" class="mobile-label">Email</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           class="mobile-input"
                           value="{{ old('email', $driver->email) }}"
                           placeholder="Enter email address">
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- 電話 --}}
                <div style="margin-bottom: 20px;">
                    <label for="phone" class="mobile-label">Phone Number</label>
                    <input type="tel" 
                           name="phone" 
                           id="phone" 
                           class="mobile-input"
                           value="{{ old('phone', $driver->phone) }}"
                           placeholder="e.g., +852 1234 5678">
                    <p style="margin-top: 4px; font-size: 12px; color: #6b7280;">Optional: Include country code for international numbers</p>
                    @error('phone')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- 角色設定 --}}
                <div style="margin-bottom: 24px;">
                    <label class="mobile-label">Role</label>
                    <div style="padding: 16px; background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 12px; color: #374151; font-size: 14px;">
                        Driver (Fixed)
                    </div>
                    <input type="hidden" name="user_role" value="driver">
                    <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">Driver role cannot be changed in this management section.</p>
                </div>
                
                {{-- 提交按鈕 --}}
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    {{-- 更新和返回按鈕同一行 --}}
                    <div style="display: flex; gap: 12px;">
                        <button type="submit" class="mobile-action-btn mobile-btn-blue" style="flex: 1;">
                            <svg style="width: 20px; height: 20px; fill: currentColor; margin-right: 8px;" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path>
                            </svg>
                            Update
                        </button>
                        
                        <a href="{{ route('admin.drivers.show', $driver->id) }}" class="mobile-action-btn mobile-btn-gray" style="flex: 1;">
                            <svg style="width: 20px; height: 20px; fill: currentColor; margin-right: 8px;" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Back
                        </a>
                    </div>
                </div>
            </form>
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

            // 表單提交前驗證
            document.getElementById('editUserForm').addEventListener('submit', function(e) {
                const username = document.getElementById('username').value.trim();
                const email = document.getElementById('email').value.trim();
                
                if (!username) {
                    e.preventDefault();
                    alert('Please enter username');
                    document.getElementById('username').focus();
                    return false;
                }
                
                if (!email) {
                    e.preventDefault();
                    alert('Please enter email address');
                    document.getElementById('email').focus();
                    return false;
                }
                
                // 簡單的郵箱格式驗證
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('Please enter a valid email address');
                    document.getElementById('email').focus();
                    return false;
                }
            });
        </script>
    @endif
@endsection