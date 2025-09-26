@section('Title', 'Trip History')
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Trip History') }}
            </h2>
            <a href="{{ route('dashboard') }}" 
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-md">
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- 篩選器和搜尋欄 -->
        <div class="mb-6 space-y-4">
            <!-- 付款狀態篩選器 -->
            <div class="flex items-center gap-2">
                <button id="filter-paid" 
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-green-600 text-white border-2 border-green-600 filter-btn active">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('Paid') }}
                    </span>
                </button>
                <button id="filter-unpaid" 
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-2 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 filter-btn">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('Unpaid') }}
                    </span>
                </button>
            </div>
            
            <!-- 搜尋欄 -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="trip-search" 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="{{ __('Search trips by destination or pickup location...') }}">
            </div>
        </div>

        <!-- 行程卡片 -->
        <div class="space-y-4" id="trips-container">
            @forelse ($payments as $payment)
                @php
                    $trip = $payment->trip;
                    $departureTime = \Carbon\Carbon::parse($trip->planned_departure_time);
                    $now = \Carbon\Carbon::now();
                    $isExpired = $departureTime < $now;
                    $isToday = $departureTime->isToday();
                    $isUpcoming = $departureTime > $now && $departureTime->diffInDays($now) <= 1;
                @endphp
                
                <a href="{{ route('trips.show', ['id' => $trip->id]) }}" 
                    class="block bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:scale-[1.01] transition-all duration-200 trip-card {{ $payment->paid ? 'paid-trip' : 'unpaid-trip' }}"
                    data-search-text="{{ strtolower($trip->dropoff_location . ' ' . $payment->pickup_location) }}"
                    data-paid="{{ $payment->paid ? 'true' : 'false' }}"
                    style="{{ $payment->paid ? '' : 'display: none;' }}">
                    
                    <!-- 頂部狀態指示器 -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            @if ($isUpcoming)
                                <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-200 text-xs font-semibold rounded-full">
                                    {{ __('Upcoming') }}
                                </span>
                            @elseif ($isToday && !$isExpired)
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full animate-pulse">
                                    {{ __('Today') }}
                                </span>
                            @elseif ($isExpired)
                                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-semibold rounded-full">
                                    {{ __('Completed') }}
                                </span>
                            @endif
                            
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $payment->paid ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200' }}">
                                {{ __($payment->paid ? 'Paid' : 'Unpaid') }}
                            </span>
                        </div>
                        
                        <div class="text-right">
                            <div class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                HK$ {{ number_format($payment->amount, 0) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ ucfirst($payment->type) }}
                            </div>
                        </div>
                    </div>

                    <!-- 路線信息 -->
                    <div class="mb-4">
                        <div class="flex items-start gap-3">
                            <div class="flex flex-col items-center mt-1">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div class="w-0.5 h-8 bg-gray-300 dark:bg-gray-600 my-1"></div>
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            </div>
                            <div class="flex-1 space-y-3">
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Pickup') }}</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 break-words">
                                        {{ $payment->pickup_location }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Destination') }}</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 break-words">
                                        {{ $trip->dropoff_location }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 時間和詳情 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div>
                                <div class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $departureTime->format('H:i') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $departureTime->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center text-gray-400 dark:text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No trip history') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('You haven\'t joined any trips yet.') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('dashboard') }}" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Find Trips') }}
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- 分頁 -->
        @if ($payments->hasPages())
            <div class="mt-8">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <!-- 篩選和搜尋功能 JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('trip-search');
            const tripCards = document.querySelectorAll('.trip-card');
            const filterPaidBtn = document.getElementById('filter-paid');
            const filterUnpaidBtn = document.getElementById('filter-unpaid');
            
            let currentFilter = 'paid'; // 預設顯示已付款的記錄

            // 篩選器按鈕點擊事件
            filterPaidBtn.addEventListener('click', function() {
                currentFilter = 'paid';
                updateFilterButtons();
                applyFilters();
            });

            filterUnpaidBtn.addEventListener('click', function() {
                currentFilter = 'unpaid';
                updateFilterButtons();
                applyFilters();
            });

            // 更新篩選器按鈕樣式
            function updateFilterButtons() {
                // 重置所有按鈕樣式
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.remove('bg-green-600', 'text-white', 'border-green-600');
                    btn.classList.remove('bg-red-600', 'text-white', 'border-red-600');
                    btn.classList.add('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border-gray-300', 'dark:border-gray-600');
                });

                // 設置活躍按鈕樣式
                if (currentFilter === 'paid') {
                    filterPaidBtn.classList.add('active');
                    filterPaidBtn.classList.remove('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border-gray-300', 'dark:border-gray-600');
                    filterPaidBtn.classList.add('bg-green-600', 'text-white', 'border-green-600');
                } else {
                    filterUnpaidBtn.classList.add('active');
                    filterUnpaidBtn.classList.remove('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border-gray-300', 'dark:border-gray-600');
                    filterUnpaidBtn.classList.add('bg-red-600', 'text-white', 'border-red-600');
                }
            }

            // 應用篩選和搜尋
            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();

                tripCards.forEach(function(card) {
                    const searchText = card.getAttribute('data-search-text');
                    const isPaid = card.getAttribute('data-paid') === 'true';
                    
                    // 檢查付款狀態篩選
                    const matchesPaymentFilter = (currentFilter === 'paid' && isPaid) || (currentFilter === 'unpaid' && !isPaid);
                    
                    // 檢查搜尋條件
                    const matchesSearch = searchTerm === '' || searchText.includes(searchTerm);
                    
                    // 同時滿足篩選和搜尋條件才顯示
                    if (matchesPaymentFilter && matchesSearch) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // 搜尋輸入事件
            searchInput.addEventListener('input', applyFilters);

            // 初始化顯示 paid 記錄
            updateFilterButtons();
            applyFilters();
        });
    </script>
</x-app-layout>
