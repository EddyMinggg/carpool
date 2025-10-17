@extends('admin.layout')

@section('title', 'Coupon Management - Edit')
@section('page-title', 'Edit Coupon')

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
            }
            
            .mobile-form-input {
                width: 100% !important;
                padding: 12px 16px !important;
                border: 2px solid #e5e7eb !important;
                border-radius: 8px !important;
                font-size: 16px !important;
                background: #f9fafb !important;
                transition: all 0.2s !important;
                margin-bottom: 16px !important;
            }
            
            .mobile-form-input:focus {
                outline: none !important;
                border-color: #3b82f6 !important;
                background: white !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            }
            
            .mobile-form-label {
                display: block !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                color: #374151 !important;
                margin-bottom: 8px !important;
            }
            
            .mobile-action-btn {
                width: 100% !important;
                padding: 12px !important;
                border-radius: 8px !important;
                font-weight: 600 !important;
                font-size: 16px !important;
                border: none !important;
                cursor: pointer !important;
                transition: all 0.2s !important;
                margin-bottom: 12px !important;
            }
            
            .mobile-btn-blue {
                background: #3b82f6 !important;
                color: white !important;
            }
            
            .mobile-btn-gray {
                background: #6b7280 !important;
                color: white !important;
            }
        </style>
    @endif

    {{-- ============ 桌面版內容 ============ --}}
    @if(!$isMobile)
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Coupon #{{ $coupon->id }}</h2>
        <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('code', $coupon->code) }}">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-1">Discount Amount (HK$) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="discount_amount" id="discount_amount" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('discount_amount', $coupon->discount_amount) }}">
                    @error('discount_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-1">Valid From</label>
                    <input type="date" name="valid_from" id="valid_from" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('valid_from', $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : '') }}">
                    @error('valid_from')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="valid_to" class="block text-sm font-medium text-gray-700 mb-1">Valid To</label>
                    <input type="date" name="valid_to" id="valid_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('valid_to', $coupon->valid_to ? $coupon->valid_to->format('Y-m-d') : '') }}">
                    @error('valid_to')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="enabled" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ old('enabled', $coupon->enabled) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Enabled</span>
                    </label>
                </div>
                <div class="mb-4">
                    <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-1">Usage Limit</label>
                    <input type="number" name="usage_limit" id="usage_limit" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="Leave empty for unlimited">
                    <p class="text-xs text-gray-500 mt-1">Total number of times this coupon can be used</p>
                    @error('usage_limit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="per_user_limit" class="block text-sm font-medium text-gray-700 mb-1">Per User Limit</label>
                    <input type="number" name="per_user_limit" id="per_user_limit" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('per_user_limit', $coupon->per_user_limit) }}" placeholder="Leave empty for unlimited">
                    <p class="text-xs text-gray-500 mt-1">Maximum times each user can use this coupon (e.g., 1 for newmember coupon)</p>
                    @error('per_user_limit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Update Coupon</button>
                    <a href="{{ route('admin.coupons.show', $coupon->id) }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    @endif

    {{-- ============ 移動版內容 ============ --}}
    @if($isMobile)
        <div class="mobile-form-card">
            <h2 style="font-size: 24px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0; text-align: center;">
                Edit Coupon
            </h2>
            
            <div style="background: #f3f4f6; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                <span style="font-size: 16px; font-weight: 600; color: #374151;">{{ $coupon->code }}</span>
            </div>
            
            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div style="margin-bottom: 16px;">
                    <label for="code" class="mobile-form-label">Code <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="code" id="code" class="mobile-form-input" value="{{ old('code', $coupon->code) }}">
                    @error('code')
                        <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label for="discount_amount" class="mobile-form-label">Discount Amount (HK$) <span style="color: #ef4444;">*</span></label>
                    <input type="number" step="0.01" name="discount_amount" id="discount_amount" min="0" class="mobile-form-input" value="{{ old('discount_amount', $coupon->discount_amount) }}">
                    @error('discount_amount')
                        <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label for="valid_from" class="mobile-form-label">Valid From</label>
                    <input type="date" name="valid_from" id="valid_from" class="mobile-form-input" value="{{ old('valid_from', $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : '') }}">
                    @error('valid_from')
                        <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label for="valid_to" class="mobile-form-label">Valid To</label>
                    <input type="date" name="valid_to" id="valid_to" class="mobile-form-input" value="{{ old('valid_to', $coupon->valid_to ? $coupon->valid_to->format('Y-m-d') : '') }}">
                    @error('valid_to')
                        <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: flex; align-items: center; font-size: 14px; color: #374151;">
                        <input type="checkbox" name="enabled" value="1" style="width: 16px; height: 16px; margin-right: 8px;" {{ old('enabled', $coupon->enabled) ? 'checked' : '' }}>
                        <span>Enabled</span>
                    </label>
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label for="usage_limit" class="mobile-form-label">Usage Limit</label>
                    <input type="number" name="usage_limit" id="usage_limit" min="1" class="mobile-form-input" value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="Leave empty for unlimited">
                    <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Total number of times this coupon can be used</p>
                    @error('usage_limit')
                        <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div style="margin-bottom: 24px;">
                    <label for="per_user_limit" class="mobile-form-label">Per User Limit</label>
                    <input type="number" name="per_user_limit" id="per_user_limit" min="1" class="mobile-form-input" value="{{ old('per_user_limit', $coupon->per_user_limit) }}" placeholder="Leave empty for unlimited">
                    <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Maximum times each user can use this coupon</p>
                    @error('per_user_limit')
                        <p style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="mobile-action-btn mobile-btn-blue">
                    <i class="fas fa-save" style="margin-right: 8px;"></i>
                    Update
                </button>
                
                <a href="{{ route('admin.coupons.show', $coupon->id) }}" class="mobile-action-btn mobile-btn-gray" style="text-decoration: none; display: block; text-align: center;">
                    <i class="fas fa-times" style="margin-right: 8px;"></i>
                    Cancel
                </a>
            </form>
        </div>
    @endif
@endsection
