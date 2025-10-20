@extends('admin.layout')

@section('title', 'Payment Confirmation - Global Search')
@section('page-title', 'Payment Confirmation')

@section('content')
    {{-- Mobile CSS Reset and Styles --}}
    @if($isMobile)
        <style>
            /* Mobile strict CSS reset */
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
                padding: 12px !important;
                overflow-x: hidden !important;
            }
            
            /* Mobile search card */
            .mobile-search-card {
                background: white;
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            
            .mobile-search-title {
                font-size: 18px;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 16px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .mobile-search-input {
                width: 100%;
                padding: 12px 16px;
                border: 2px solid #e5e7eb;
                border-radius: 8px;
                font-size: 16px;
                background: white;
                transition: all 0.2s;
                margin-bottom: 12px;
            }
            
            .mobile-search-input:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            
            .mobile-filter-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                margin-bottom: 16px;
            }
            
            .mobile-filter-select {
                width: 100%;
                padding: 10px 12px;
                border: 1px solid #d1d5db;
                border-radius: 6px;
                font-size: 14px;
                background: white;
            }
            
            .mobile-search-btn {
                width: 100%;
                padding: 12px;
                background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            .mobile-search-btn:hover {
                background: linear-gradient(135deg, #1d4ed8, #1e40af);
            }
            
            /* Mobile stats cards */
            .mobile-stats-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                margin-bottom: 16px;
            }
            
            .mobile-stat-card {
                background: white;
                border-radius: 8px;
                padding: 12px;
                text-align: center;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }
            
            .mobile-stat-value {
                font-size: 20px;
                font-weight: 700;
                color: #1f2937;
            }
            
            .mobile-stat-label {
                font-size: 11px;
                color: #6b7280;
                text-transform: uppercase;
                font-weight: 600;
                margin-top: 2px;
            }
            
            /* Mobile payment cards */
            .mobile-payment-card {
                background: white;
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 12px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                border-left: 4px solid transparent;
            }
            
            .mobile-payment-card.pending {
                border-left-color: #f59e0b;
            }
            
            .mobile-payment-card.confirmed {
                border-left-color: #10b981;
            }
            
            .mobile-payment-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 12px;
            }
            
            .mobile-payment-user {
                font-size: 16px;
                font-weight: 600;
                color: #1f2937;
            }
            
            .mobile-payment-amount {
                font-size: 18px;
                font-weight: 700;
                color: #3b82f6;
            }
            
            .mobile-payment-details {
                font-size: 12px;
                color: #6b7280;
                margin-bottom: 8px;
            }
            
            .mobile-payment-badges {
                display: flex;
                gap: 6px;
                flex-wrap: wrap;
                margin-bottom: 12px;
            }
            
            .mobile-payment-badge {
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 10px;
                font-weight: 600;
                text-transform: uppercase;
            }
            
            .mobile-payment-badge.group {
                background: #dbeafe;
                color: #1e40af;
            }
            
            .mobile-payment-badge.deposit {
                background: #fed7aa;
                color: #9a3412;
            }
            
            .mobile-payment-badge.remaining {
                background: #c7d2fe;
                color: #3730a3;
            }
            
            .mobile-payment-badge.pending {
                background: #fef3c7;
                color: #92400e;
            }
            
            .mobile-payment-badge.confirmed {
                background: #d1fae5;
                color: #166534;
            }
            
            .mobile-payment-badge.coupon {
                background: #fce7f3;
                color: #9f1239;
            }
            
            .mobile-payment-actions {
                display: flex;
                gap: 8px;
            }
            
            .mobile-action-btn {
                flex: 1;
                padding: 8px 12px;
                border: none;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 600;
                text-decoration: none;
                text-align: center;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            .mobile-action-btn.primary {
                background: #3b82f6;
                color: white;
            }
            
            .mobile-action-btn.primary:hover {
                background: #1d4ed8;
                color: white;
            }
            
            .mobile-action-btn.secondary {
                background: #f3f4f6;
                color: #374151;
                border: 1px solid #d1d5db;
            }
            
            .mobile-action-btn.secondary:hover {
                background: #e5e7eb;
                color: #374151;
            }
            
            /* Hide desktop elements on mobile */
            .desktop-only {
                display: none !important;
            }
            
            /* Mobile pagination */
            .mobile-pagination {
                display: flex;
                justify-content: center;
                gap: 8px;
                margin-top: 20px;
            }
            
            .mobile-pagination a,
            .mobile-pagination span {
                padding: 8px 12px;
                border: 1px solid #d1d5db;
                border-radius: 6px;
                text-decoration: none;
                color: #374151;
                font-size: 14px;
            }
            
            .mobile-pagination .active {
                background: #3b82f6;
                color: white;
                border-color: #3b82f6;
            }
        </style>
    @endif
    
    <style>
        /* Desktop styles - hide on mobile */
        @media (max-width: 768px) {
            .desktop-only {
                display: none !important;
            }
        }

        /* Mobile styles - hide on desktop */
        @media (min-width: 769px) {
            .mobile-only {
                display: none !important;
            }
        }
    </style>

    @if($isMobile)
        <!-- Mobile Layout -->
        <div class="mobile-only" style="padding: 12px; background-color: #f1f5f9; min-height: 100vh;">
            
            <!-- Mobile Search Section -->
            <div class="mobile-search-card">
                <div class="mobile-search-title">
                    🔍 Search Payments
                </div>
                
                <form method="GET" action="{{ route('admin.payment-confirmation.global') }}">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Reference code, phone, trip ID..."
                           class="mobile-search-input">
                    
                    <div class="mobile-filter-grid">
                        <select name="status" class="mobile-filter-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        </select>
                        
                        <select name="type" class="mobile-filter-select">
                            <option value="">All Types</option>
                            <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                            <option value="remaining" {{ request('type') == 'remaining' ? 'selected' : '' }}>Remaining</option>
                            <option value="group_full_payment" {{ request('type') == 'group_full_payment' ? 'selected' : '' }}>Group Booking</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="mobile-search-btn">
                        🔍 Search Payments
                    </button>
                </form>
            </div>

            <!-- Mobile Statistics -->
            <div class="mobile-stats-grid">
                <div class="mobile-stat-card">
                    <div class="mobile-stat-value">{{ $stats['total_pending'] }}</div>
                    <div class="mobile-stat-label">Pending</div>
                </div>
                <div class="mobile-stat-card">
                    <div class="mobile-stat-value">{{ $stats['total_confirmed'] }}</div>
                    <div class="mobile-stat-label">Confirmed</div>
                </div>
                <div class="mobile-stat-card">
                    <div class="mobile-stat-value">HK$ {{ number_format($stats['pending_amount'], 0) }}</div>
                    <div class="mobile-stat-label">Pending Amount</div>
                </div>
                <div class="mobile-stat-card">
                    <div class="mobile-stat-value">HK$ {{ number_format($stats['confirmed_amount'], 0) }}</div>
                    <div class="mobile-stat-label">Confirmed Amount</div>
                </div>
            </div>

            <!-- Mobile Payment Results -->
            @if($payments->count() > 0)
                @foreach($payments as $payment)
                    <div class="mobile-payment-card {{ $payment->paid ? 'confirmed' : 'pending' }}">
                        <div class="mobile-payment-header">
                            <div>
                                <div class="mobile-payment-user">{{ $payment->user_phone }}</div>
                                <div class="mobile-payment-details">
                                    Trip #{{ $payment->trip->id }} • {{ $payment->trip->dropoff_location }}
                                </div>
                                <div class="mobile-payment-details">
                                    {{ $payment->created_at->format('M d, Y H:i') }}
                                </div>
                            </div>
                            <div class="mobile-payment-amount">
                                HK$ {{ number_format($payment->amount, 0) }}
                            </div>
                        </div>
                        
                        <div class="mobile-payment-badges">
                            @if($payment->type === 'group_full_payment')
                                <span class="mobile-payment-badge group">👥 Group</span>
                            @elseif($payment->type === 'deposit')
                                <span class="mobile-payment-badge deposit">💰 Deposit</span>
                            @elseif($payment->type === 'remaining')
                                <span class="mobile-payment-badge remaining">💳 Remaining</span>
                            @endif
                            
                            <span class="mobile-payment-badge {{ $payment->paid ? 'confirmed' : 'pending' }}">
                                {{ $payment->paid ? '✅ Confirmed' : '⏳ Pending' }}
                            </span>
                            
                            @if($payment->reference_code)
                                <span class="mobile-payment-badge" style="background: #e5e7eb; color: #374151;">
                                    {{ $payment->reference_code }}
                                </span>
                            @endif
                            
                            @if($payment->couponUsage)
                                <span class="mobile-payment-badge coupon">
                                    🎟️ {{ $payment->couponUsage->coupon->code }} (-HK$ {{ number_format($payment->couponUsage->discount_amount, 0) }})
                                </span>
                            @endif
                        </div>
                        
                        <div class="mobile-payment-actions">
                            @if(!$payment->paid)
                                <a href="{{ route('admin.payment-confirmation.show', $payment) }}" 
                                   class="mobile-action-btn primary">
                                   ✅ Confirm Payment
                                </a>
                            @endif
                            <a href="{{ route('admin.trips.show', $payment->trip) }}" 
                               class="mobile-action-btn secondary">
                               🚗 View Trip
                            </a>
                        </div>
                    </div>
                @endforeach
                
                <!-- Mobile Pagination -->
                <div class="mobile-pagination">
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            @else
                <div style="background: white; border-radius: 12px; padding: 24px; text-align: center; margin-top: 20px;">
                    <i class="fas fa-search" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;"></i>
                    <h3 style="color: #6b7280; margin-bottom: 8px;">No payments found</h3>
                    <p style="color: #9ca3af; font-size: 14px;">
                        Try adjusting your search criteria or check if there are any pending payments.
                    </p>
                </div>
            @endif
            
        </div>
    @else
        <!-- Desktop Layout -->
        <div class="desktop-only">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Payment Confirmation</h2>
                    <p class="text-gray-600">Search and confirm payments across all trips</p>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Search & Filter</h3>
                    
                    <form method="GET" action="{{ route('admin.payment-confirmation.global') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Search
                                </label>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Reference code, phone, trip ID, destination..."
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status
                                </label>
                                <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Payment Type
                                </label>
                                <select name="type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200">
                                    <option value="">All Types</option>
                                    <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                                    <option value="remaining" {{ request('type') == 'remaining' ? 'selected' : '' }}>Remaining</option>
                                    <option value="group_full_payment" {{ request('type') == 'group_full_payment' ? 'selected' : '' }}>Group Booking</option>
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                                    🔍 Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-yellow-600">{{ $stats['total_pending'] }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pending Payments</div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $stats['total_confirmed'] }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Confirmed Payments</div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-2xl font-bold text-yellow-600">HK$ {{ number_format($stats['pending_amount'], 0) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pending Amount</div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-2xl font-bold text-green-600">HK$ {{ number_format($stats['confirmed_amount'], 0) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Confirmed Amount</div>
                    </div>
                </div>
            </div>

            <!-- Payment Results -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Payment Results ({{ $payments->total() }} found)
                    </h3>
                    
                    @if($payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User & Trip</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment Info</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Coupon</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($payments as $payment)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $payment->user_phone }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    Trip #{{ $payment->trip->id }} - {{ Str::limit($payment->trip->dropoff_location, 30) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-wrap gap-1">
                                                    @if($payment->type === 'group_full_payment')
                                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                            👥 Group
                                                        </span>
                                                    @elseif($payment->type === 'deposit')
                                                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                                                            💰 Deposit
                                                        </span>
                                                    @elseif($payment->type === 'remaining')
                                                        <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                                            💳 Remaining
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($payment->reference_code)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        Ref: {{ $payment->reference_code }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                HK$ {{ number_format($payment->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($payment->couponUsage)
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        <span class="px-2 py-1 text-xs font-medium bg-pink-100 text-pink-800 rounded-full">
                                                            🎟️ {{ $payment->couponUsage->coupon->code }}
                                                        </span>
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        -HK$ {{ number_format($payment->couponUsage->discount_amount, 2) }}
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">No coupon</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($payment->paid)
                                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                        ✅ Confirmed
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                        ⏳ Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $payment->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                @if(!$payment->paid)
                                                    <a href="{{ route('admin.payment-confirmation.show', $payment) }}" 
                                                       class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                        Confirm
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.trips.show', $payment->trip) }}" 
                                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    View Trip
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Desktop Pagination -->
                        <div class="mt-6">
                            {{ $payments->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 text-6xl mb-4">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No payments found</h3>
                            <p class="text-gray-500 dark:text-gray-400">
                                Try adjusting your search criteria or check if there are any pending payments.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus on search input
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput && !searchInput.value) {
                searchInput.focus();
            }
            
            // Quick search functionality (could be enhanced with AJAX)
            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    // Could add live search here if needed
                });
            }
        });
    </script>
@endsection