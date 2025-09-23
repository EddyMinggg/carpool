@extends('admin.layout')

@section('title', 'Order Details')
@section('page-title', 'Order Details')

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
            
            .role-driver {
                background: #dbeafe;
                color: #1e40af;
            }
            
            .role-passenger {
                background: #d1fae5;
                color: #065f46;
            }
            
            /* 狀態圖標 */
            .status-icon {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                color: white;
                flex-shrink: 0;
                margin: 0 auto 16px auto;
            }
            
            .status-driver {
                background: #3b82f6;
            }
            
            .status-passenger {
                background: #10b981;
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
            <h2 class="text-2xl font-bold text-gray-800">Order Details #{{ $order->id }}</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-900 text-sm">← Back to Order List</a>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 py-2">
                <div class="py-3">
                    <p class="text-sm text-gray-500">User</p>
                    <p class="text-gray-900">{{ $order->user->username ?? 'Deleted User' }}</p>
                </div>
                <div class="py-3">
                    <p class="text-sm text-gray-500">Trip</p>
                    <p class="text-gray-900">{{ $order->trip->pickup_location ?? '-' }} → {{ $order->trip->dropoff_location ?? '-' }}</p>
                </div>
                <div class="py-3">
                    <p class="text-sm text-gray-500">Role</p>
                    <p class="text-gray-900">{{ ucfirst($order->join_role) }}</p>
                </div>
                <div class="py-3">
                    <p class="text-sm text-gray-500">Pickup Location</p>
                    <p class="text-gray-900">{{ $order->pickup_location ?? '-' }}</p>
                </div>
                <div class="py-3">
                    <p class="text-sm text-gray-500">Fee (¥)</p>
                    <p class="text-gray-900">{{ number_format($order->user_fee, 2) }}</p>
                </div>
                <div class="py-3">
                    <p class="text-sm text-gray-500">Created At</p>
                    <p class="text-gray-900">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- ============ 移動版內容 ============ --}}
    @if($isMobile)
        {{-- 訂單狀態和基本信息卡片 --}}
        <div class="mobile-info-card">
            {{-- 狀態圖標 --}}
            <div style="text-align: center; margin-bottom: 20px;">
                <div class="status-icon {{ $order->join_role === 'driver' ? 'status-driver' : 'status-passenger' }}">
                    @if($order->join_role === 'driver')
                        <i class="fas fa-car"></i>
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                
                {{-- 訂單ID和角色 --}}
                <div style="text-align: center;">
                    <h2 style="font-size: 24px; font-weight: 700; color: #1f2937; margin: 0 0 8px 0; max-width: 100%; word-wrap: break-word;">
                        Order #{{ $order->id }}
                    </h2>
                    <span class="role-badge {{ $order->join_role === 'driver' ? 'role-driver' : 'role-passenger' }}">
                        {{ ucfirst($order->join_role) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- 用戶信息卡片 --}}
        <div class="mobile-info-card">
            <h3 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px;">
                User Information
            </h3>
            
            {{-- 用戶名 --}}
            <div style="margin-bottom: 16px;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 4px; font-weight: 600;">User</div>
                <div style="font-size: 16px; color: #1f2937; word-wrap: break-word; max-width: 100%;">
                    {{ $order->user->username ?? 'Deleted User' }}
                </div>
            </div>
            
            {{-- 接送地點 --}}
            <div>
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 4px; font-weight: 600;">Pickup Location</div>
                <div style="font-size: 16px; color: #1f2937; word-wrap: break-word;">
                    {{ $order->pickup_location ?? '-' }}
                </div>
            </div>
        </div>

        {{-- 行程信息卡片 --}}
        <div class="mobile-info-card">
            <h3 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px;">
                Trip Information
            </h3>
            
            {{-- 行程路線 --}}
            <div style="margin-bottom: 16px;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 4px; font-weight: 600;">Trip Route</div>
                <div style="font-size: 16px; color: #1f2937; word-wrap: break-word;">
                    {{ $order->trip->pickup_location ?? '-' }} → {{ $order->trip->dropoff_location ?? '-' }}
                </div>
            </div>
            
            {{-- 費用 --}}
            <div style="margin-bottom: 16px;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 4px; font-weight: 600;">Fee</div>
                <div style="font-size: 20px; color: #059669; font-weight: 700;">
                    ¥{{ number_format($order->user_fee, 2) }}
                </div>
            </div>
            
            {{-- 創建時間 --}}
            <div>
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 4px; font-weight: 600;">Created At</div>
                <div style="font-size: 16px; color: #1f2937;">
                    {{ $order->created_at->format('Y-m-d H:i') }}
                </div>
            </div>
        </div>

        {{-- 操作按鈕卡片 --}}
        <div class="mobile-info-card">
            <h3 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px;">
                Actions
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                {{-- 返回按鈕 --}}
                <a href="{{ route('admin.orders.index') }}" class="mobile-action-btn mobile-btn-gray">
                    <svg style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    Back
                </a>
            </div>
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
    @endif
@endsection
