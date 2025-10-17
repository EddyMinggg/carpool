@extends('admin.layout')

@section('page-title', 'Create New Driver')

@section('title', 'Create New Driver')

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
            
            /* 表單容器樣式 */
            .mobile-form-container {
                background: white !important;
                border-radius: 8px !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
                padding: 20px !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            
            /* 輸入框樣式 */
            .mobile-input {
                width: 100% !important;
                padding: 12px !important;
                border: 1px solid #d1d5db !important;
                border-radius: 6px !important;
                font-size: 16px !important;
                box-sizing: border-box !important;
            }
            
            .mobile-input:focus {
                outline: none !important;
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            }
            
            /* 按鈕樣式 */
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
            }
            
            .mobile-btn-primary {
                background: #3b82f6 !important;
                color: white !important;
            }
            
            .mobile-btn-primary:hover {
                background: #2563eb !important;
            }
            
            .mobile-btn-secondary {
                background: #f3f4f6 !important;
                color: #374151 !important;
                margin-top: 12px !important;
            }
            
            .mobile-btn-secondary:hover {
                background: #e5e7eb !important;
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
            <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
                <form action="{{ route('admin.drivers.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Username -->
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" id="username" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('username') }}" required>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" name="phone" id="phone" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('phone') }}" 
                            placeholder="e.g., +852 1234 5678">
                        <p class="mt-1 text-xs text-gray-500">Optional: Include country code for international numbers</p>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                            Create Driver
                        </button>
                        <a href="{{ route('admin.drivers.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- 移動版內容 --}}
    @if($isMobile)
        <div class="mobile-container">
            <div class="mobile-form-container">
                <form action="{{ route('admin.drivers.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 16px; border-radius: 6px; margin-bottom: 16px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li style="margin: 4px 0;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Username -->
                    <div style="margin-bottom: 16px;">
                        <label for="mobile-username" style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px;">Username <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="username" id="mobile-username" 
                            class="mobile-input"
                            value="{{ old('username') }}" required>
                        @error('username')
                            <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div style="margin-bottom: 16px;">
                        <label for="mobile-email" style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px;">Email <span style="color: #dc2626;">*</span></label>
                        <input type="email" name="email" id="mobile-email" 
                            class="mobile-input"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div style="margin-bottom: 16px;">
                        <label for="mobile-phone" style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px;">Phone Number</label>
                        <input type="tel" name="phone" id="mobile-phone" 
                            class="mobile-input"
                            value="{{ old('phone') }}" 
                            placeholder="e.g., +852 1234 5678">
                        <p style="margin-top: 4px; font-size: 12px; color: #6b7280;">Optional: Include country code for international numbers</p>
                        @error('phone')
                            <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div style="margin-bottom: 16px;">
                        <label for="mobile-password" style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px;">Password <span style="color: #dc2626;">*</span></label>
                        <input type="password" name="password" id="mobile-password" 
                            class="mobile-input" required>
                        @error('password')
                            <p style="margin-top: 4px; font-size: 14px; color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div style="margin-bottom: 24px;">
                        <label for="mobile-password-confirmation" style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px;">Confirm Password <span style="color: #dc2626;">*</span></label>
                        <input type="password" name="password_confirmation" id="mobile-password-confirmation" 
                            class="mobile-input" required>
                    </div>

                    <!-- Submit Buttons -->
                    <div>
                        <button type="submit" class="mobile-btn mobile-btn-primary">
                            Create Driver
                        </button>
                        <a href="{{ route('admin.drivers.index') }}" class="mobile-btn mobile-btn-secondary" style="display: block; text-align: center; text-decoration: none;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection