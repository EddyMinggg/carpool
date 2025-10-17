@section('Title', 'Trip History')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Trip History') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- 篩選器和搜尋欄 -->
        <div class="mb-6 space-y-4">
            <!-- 付款狀態篩選器 -->
            <div class="flex items-center gap-2">
                <button id="filter-paid"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    {{-- class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-green-600 dark:bg-green-700 text-gray-200 filter-btn active"> --}}
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        {{ __('Paid') }}
                    </span>
                </button>
                <button id="filter-unpaid"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('Unpaid') }}
                    </span>
                </button>
            </div>

            <!-- 搜尋欄 -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="trip-search"
                    class="block w-full pl-10 pr-3 py-2 text-sm border-gray-300 dark:border-gray-700 bg-secondary dark:bg-secondary-dark dark:text-gray-300 focus:border-primary dark:focus:border-primary-dark focus:ring-primary dark:focus:ring-primary-dark rounded-md shadow-sm"
                    placeholder="{{ __('Search trips by destination or pickup location...') }}">
            </div>

            <!-- 四人優惠政策說明 -->
            <div
                class="bg-primary-opaque dark:bg-primary-opaque-dark border border-primary dark:border-primary-dark rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 text-primary mt-0.5">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 text-sm">
                        <div class="font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('4-Person Discount Policy') }}</div>
                        <div class="text-gray-700 dark:text-gray-400 leading-relaxed">
                            {{ __('All passengers pay HK$275 initially. HK$50 refund per person processed after trip deadline if 4+ people confirmed with no cancellations. Refunds handled by admin within 48 hours post-deadline.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 行程卡片 -->
        <div class="space-y-4" id="trips-container">
            @forelse ($tripJoins as $tripJoin)
                @php
                    $trip = $tripJoin->trip;
                    $departureTime = \Carbon\Carbon::parse($trip->planned_departure_time);
                    $now = \Carbon\Carbon::now();
                    $isExpired = $departureTime < $now;
                    $isToday = $departureTime->isToday();
                    $isUpcoming = $departureTime > $now && $departureTime->diffInDays($now) <= 1;
                @endphp

                @php
                    // Safely get trip join using direct query
                    $userTripJoin = \App\Models\TripJoin::where('trip_id', $tripJoin->trip_id)
                        ->where('user_phone', $tripJoin->user_phone)
                        ->first();
                    $pickupLocation = $userTripJoin ? $userTripJoin->pickup_location : __('Not specified');
                @endphp
                <a href="{{ route('trips.show', ['id' => $trip->id]) }}"
                    class="block bg-secondary dark:bg-secondary-accent rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:scale-[1.01] transition-all duration-200 trip-card {{ $tripJoin->paid ? 'paid-trip' : 'unpaid-trip' }}"
                    data-search-text="{{ strtolower($trip->dropoff_location . ' ' . $pickupLocation) }}"
                    data-paid="{{ $tripJoin->payment_confirmed == 1 ? 'true' : 'false' }}"
                    style="{{ $tripJoin->payment_confirmed == 1 ? '' : 'display: none;' }}">

                    <!-- 頂部狀態指示器 -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            @if ($isUpcoming)
                                <span
                                    class="px-3 py-1 bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-200 text-xs font-semibold rounded-full">
                                    {{ __('Upcoming') }}
                                </span>
                            @elseif ($isToday && !$isExpired)
                                <span
                                    class="px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full animate-pulse">
                                    {{ __('Today') }}
                                </span>
                            @elseif ($isExpired)
                                <span
                                    class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-semibold rounded-full">
                                    {{ __('Completed') }}
                                </span>
                            @endif

                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full {{ $tripJoin->payment_confirmed == 1 ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200' }}">
                                {{ __($tripJoin->payment_confirmed == 1 ? 'Paid' : 'Unpaid') }}
                            </span>
                        </div>

                        <div class="text-right">
                            <div class="text-lg font-bold text-primary-accent">
                                HK$ {{ number_format($tripJoin->user_fee, 0) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ ucfirst($tripJoin->type) }}
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
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        {{ __('Pickup') }}</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 break-words">
                                        {{ $pickupLocation }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        {{ __('Destination') }}</div>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No trip history') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('You haven\'t joined any trips yet.') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Find Trips') }}
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- 分頁 -->
        @if ($tripJoins->hasPages())
            <div class="mt-8">
                {{ $tripJoins->links() }}
            </div>
        @endif
    </div>

    <!-- 篩選和搜尋功能 JavaScript -->
    <script type="module">
        $(document).ready(function() {
            const searchInput = $('#trip-search');
            const tripCards = $('.trip-card');
            const filterPaidBtn = $('#filter-paid');
            const filterUnpaidBtn = $('#filter-unpaid');

            let currentFilter = 'paid'; // Default to showing paid records

            // Filter button click events
            filterPaidBtn.on('click', function() {
                currentFilter = 'paid';
                filterPaidBtn.removeClass('bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300');
                filterPaidBtn.addClass('bg-green-600 dark:bg-green-700 text-gray-200');
                filterUnpaidBtn.removeClass('bg-red-600 dark:bg-red-700 text-gray-200');
                filterUnpaidBtn.addClass('bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300');
                applyFilters();
            });

            filterUnpaidBtn.on('click', function() {
                currentFilter = 'unpaid';
                filterUnpaidBtn.removeClass('bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300');
                filterUnpaidBtn.addClass('bg-red-600 dark:bg-red-700 text-gray-200');
                filterPaidBtn.removeClass('bg-green-600 dark:bg-green-700 text-gray-200');
                filterPaidBtn.addClass('bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300');
                applyFilters();
            });

            // Apply filters and search
            function applyFilters() {
                const searchTerm = searchInput.val().toLowerCase();

                tripCards.each(function() {
                    const card = $(this);
                    console.log(card.data);

                    const searchText = card.data('search-text');
                    const isPaid = card.data('paid') === true;

                    const matchesPaymentFilter = (currentFilter === 'paid' && isPaid) || (currentFilter ===
                        'unpaid' && !isPaid);
                    const matchesSearch = searchTerm === '' || searchText.includes(searchTerm);

                    if (matchesPaymentFilter && matchesSearch) {
                        card.show();
                    } else {
                        card.hide();
                    }
                });
            }

            // Search input event
            searchInput.on('input', applyFilters);

            // Initialize displaying paid records
            filterPaidBtn.click();
            applyFilters();
        });
    </script>
</x-app-layout>
