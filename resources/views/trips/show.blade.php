@section('Title', $trip->dropoff_location)
<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            {{ __('Trip Details') }}
        </h2>
    </x-slot>


    <div class="overlay">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 pb-12">

        <!-- 消息顯示 -->
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg"
                x-data="{ show: true }" x-show="show" x-transition>
                {{ session('success') }}
                <button @click="show = false"
                    class="float-right text-green-500 hover:text-green-700 ml-2">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg"
                x-data="{ show: true }" x-show="show" x-transition>
                {{ session('error') }}
                <button @click="show = false" class="float-right text-red-500 hover:text-red-700 ml-2">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg"
                x-data="{ show: true }" x-show="show" x-transition>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button @click="show = false" class="float-right text-red-500 hover:text-red-700 ml-2">&times;</button>
            </div>
        @endif

        <!-- 行程資訊卡片 -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex mb-6 items-center">
                {{-- <span class="text-gray-600 dark:text-gray-300">{{ __('Status') }}</span> --}}
                <span
                    class="px-2 py-1 rounded-md text-xs
                        @if ($trip->trip_status === 'awaiting') bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200
                        @elseif($trip->trip_status === 'voting') bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200
                        @elseif($trip->trip_status === 'departed') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200
                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif">
                    {{ ucfirst($trip->trip_status) }}
                </span>
            </div>
            <!-- 路線顯示 - 響應式佈局：手機垂直，桌面水平 -->
            <div class="mb-4">
                @php
                    // 如果用戶已有預訂記錄，優先顯示數據庫中的實際地址
                    // 如果沒有預訂記錄，則顯示session中的臨時選擇
                    $userJoin = $trip->joins->where('user_phone', $userPhone)->first();
                    $confirmedLocation = $userJoin ? $userJoin->pickup_location : null;
                    $sessionLocation = session('location');
                    
                    // 優先級：已確認的預訂地址 > session臨時地址
                    $displayLocation = $confirmedLocation ?: $sessionLocation;
                @endphp

                <!-- 移動端垂直佈局 -->
                <div class="block md:hidden">
                    <!-- Pickup -->
                    <div class="flex items-start text-sm space-x-3">
                        <div class="flex-1 min-w-0 location-container">
                            <div class="flex items-center mb-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2 flex-shrink-0"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Pickup') }}</div>
                            </div>
                            <div id="pickup_location_display"
                                class="text-gray-900 dark:text-gray-100 font-medium leading-tight location-display">
                                <span>{{ $displayLocation ?: __('Select pickup location') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 箭頭 (向下) -->
                    <div class="flex justify-center my-4">
                        <span class="text-gray-400 dark:text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </div>

                    <!-- Destination -->
                    <div class="flex items-start text-sm space-x-3">
                        <div class="flex-1 min-w-0 location-container">
                            <div class="flex items-center mb-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2 flex-shrink-0"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Destination') }}</div>
                            </div>
                            <div class="text-gray-900 dark:text-gray-100 font-medium leading-tight location-display">
                                <span>{{ $trip->dropoff_location }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 桌面端水平佈局 -->
                <div class="hidden md:flex md:items-center md:justify-between md:space-x-4">
                    <!-- Pickup -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center mb-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2 flex-shrink-0"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Pickup') }}</div>
                        </div>
                        <div id="pickup_location_display_desktop"
                            class="text-gray-900 dark:text-gray-100 font-medium leading-tight location-display">
                            <span>{{ $displayLocation ?: __('Select pickup location') }}</span>
                        </div>
                    </div>

                    <!-- 箭頭 (向右) -->
                    <div class="flex-shrink-0 mx-4">
                        <span class="text-gray-400 dark:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                    </div>

                    <!-- Destination -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center mb-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2 flex-shrink-0"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Destination') }}</div>
                        </div>
                        <div class="text-gray-900 dark:text-gray-100 font-medium leading-tight location-display">
                            <span>{{ $trip->dropoff_location }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-start mt-6">
                <div>
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $departureTime->format('H:i') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $departureTime->format('Y-m-d') }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        HK$ {{ number_format($price, 0) }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Per person') }}
                    </div>
                </div>
            </div>

            <hr class="my-4 border-gray-200 dark:border-gray-600">

            <div>
                <div class="flex justify-between items-center mt-4 mb-2">
                    <span class="text-gray-600 dark:text-gray-300">{{ __('Joined User') }}</span>
                    <span
                        class="font-semibold text-gray-900 dark:text-gray-100">{{ $currentPeople }}/{{ $trip->max_people }}</span>
                </div>
            </div>
        </div>

        <!-- 司機資訊區域 -->
        @if ($assignedDriver)
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 shadow-md border border-blue-200 dark:border-blue-800 mt-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ __('Driver Information') }}
                    </h3>
                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full">
                        🚗 {{ __('Assigned') }}
                    </span>
                </div>

                <div class="flex items-center gap-4 mb-4">
                    <!-- 司機頭像 -->
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center shadow-lg">
                        <span class="text-white font-semibold text-xl">
                            {{ strtoupper(substr($assignedDriver->username, 0, 1)) }}
                        </span>
                    </div>
                    
                    <!-- 司機基本資訊 -->
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 text-lg">
                            {{ $assignedDriver->username }}
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                                {{ $assignedDriver->email }}
                            </span>
                        </p>
                        @if($assignedDriver->phone)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $assignedDriver->phone }}
                                </span>
                            </p>
                        @endif
                    </div>
                    
                    <!-- 聯絡司機按鈕 -->
                    @if($assignedDriver->phone)
                        <div class="flex flex-col gap-2">
                            <!-- 撥打電話 -->
                            <a href="tel:{{ $assignedDriver->phone }}" 
                               class="inline-flex items-center justify-center w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full transition-colors shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </a>
                            
                            <!-- WhatsApp -->
                            <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $assignedDriver->phone) }}" 
                               target="_blank"
                               class="inline-flex items-center justify-center w-12 h-12 text-white rounded-full transition-colors shadow-lg"
                               style="background-color: #25D366;">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.787"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- 司機狀態和提醒 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900 dark:text-gray-100 text-sm mb-1">
                                {{ __('Driver has been assigned to this trip') }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-400 text-sm">
                                {{ __('You can contact the driver directly using the buttons above when the trip time approaches.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-6 shadow-md border border-yellow-200 dark:border-yellow-800 mt-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/50 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-1">
                            {{ __('Driver Assignment Pending') }}
                        </h3>
                        <p class="text-yellow-700 dark:text-yellow-300 text-sm">
                            {{ __('A driver will be assigned to this trip closer to the departure time.') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- 成員列表 -->
        @if ($trip->joins->isNotEmpty())
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 mt-4">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">{{ __('Members') }}</h3>
                @foreach ($trip->joins as $join)
                    <div
                        class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                {{-- <span
                                    class="text-blue-600 dark:text-blue-300 font-semibold text-sm">{{ substr($join->user->username, 0, 1) }}</span> --}}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $join->user_phone }}
                                </div>
                                {{-- 不再顯示任何地址信息，在等待司機區域會顯示自己的地址 --}}
                            </div>
                        </div>
                        <div class="text-sm">
                            @if ($join->user_id === auth()->id())
                                <span
                                    class="px-2 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded text-xs">
                                    {{ __('You') }}
                                </span>
                            @else
                                <span class="text-gray-500 dark:text-gray-400 text-xs">
                                    {{ __('Member') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- 倒計時區域 -->
        <div id="cd" class="hidden bg-orange-600 text-white rounded-xl p-4 text-center shadow-md mt-6">
            <div class="text-sm mb-1">{{ __('Departure in') }}</div>
            <div class="text-2xl font-bold">
                <span id="cd-hours">--</span> :
                <span id="cd-minutes">--</span> :
                <span id="cd-seconds">--</span>
            </div>
        </div>

        <!-- 等待司機區域 (時間到了後顯示) -->
        <div id="waiting-driver" class="hidden space-y-4 mt-6">
            <!-- 司機信息卡片 -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Driver Information') }}
                    </h3>
                    <span
                        class="px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full animate-pulse">
                        {{ __('Trip Started') }}
                    </span>
                </div>

                @if ($assignedDriver)
                    <!-- 顯示實際分配的司機 -->
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                            <span
                                class="text-blue-600 dark:text-blue-300 font-semibold text-lg">{{ substr($assignedDriver->name ?? $assignedDriver->username, 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $assignedDriver->name ?? $assignedDriver->username }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Driver') }}</div>
                        </div>
                        @if ($assignedDriver->phone)
                            <div class="flex gap-2">
                                <a href="tel:{{ $assignedDriver->phone }}"
                                    class="p-2 bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-300 rounded-lg hover:bg-green-200 dark:hover:bg-green-800 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                </a>
                                <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $assignedDriver->phone) }}"
                                    class="p-2 text-white rounded-lg hover:opacity-80 transition"
                                    style="background-color: #25D366;">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.787">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- 尚未分配司機 -->
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 dark:text-gray-400 font-semibold text-lg">?</span>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-500 dark:text-gray-400">
                                {{ __('Driver assigning...') }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Please wait for driver assignment') }}</div>
                        </div>
                    </div>
                @endif

                <!-- 基本信息 -->
                <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            {{ __('Your pickup location') }}
                        </div>
                        @php
                            // 等待司機時顯示確定的接送地址（trip_join 表中的記錄）
                            $userJoin = $trip->joins->where('user_phone', $userPhone)->first();
                            $confirmedLocation = $userJoin ? $userJoin->pickup_location : null;
                        @endphp
                        <div class="font-medium text-gray-900 dark:text-gray-100 location-display">
                            <span>{{ $confirmedLocation ?: __('Location not set') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 安全和聯絡功能 -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 text-center">
                    {{ __('Emergency Contact') }}</h3>
                <div class="space-y-3">
                    <!-- 香港緊急電話 -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Hong Kong') }}
                        </div>
                        <a href="tel:999"
                            class="flex items-center justify-center gap-2 py-2 px-3 bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-300 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <span>999 - {{ __('Emergency') }}</span>
                        </a>
                    </div>

                    <!-- 內地緊急電話 -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Mainland China') }}</div>
                        <div class="grid grid-cols-1 gap-2">
                            <a href="tel:110"
                                class="flex items-center justify-center gap-2 py-2 px-3 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                                <span>110 - {{ __('Police') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 簡單提醒 -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-blue-900 dark:text-blue-100 text-sm">
                            {{ __('Trip Started') }}
                        </div>
                        <div class="text-blue-700 dark:text-blue-300 text-sm mt-1">
                            {{ __('Please wait for the driver to contact you. Make sure your phone is accessible.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($hasJoined)
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 mt-4">
                <div class="flex flex-col items-center gap-3">
                    <div class="text-blue-600 dark:text-blue-400 mt-0.5">
                        <i class="fas fa-exclamation-circle fa-2x" style="font-size: 2.5rem;"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-blue-900 dark:text-blue-100 text-xl text-center my-4">
                            {{ __('Reminder') }}
                        </div>
                        <div class="text-blue-700 dark:text-blue-300 text-sm mt-1">
                            {{ __('Complete your payment to secure your spot! Payment confirmation required before departure.') }}
                        </div>
                        <div class="text-blue-700 dark:text-blue-300 text-sm mt-1 font-bold">
                            {{ __('Full Amount:') . " HK$" . number_format($price, 0) }}
                        </div>
                    </div>
                    <div class="w-full mt-6">
                        <div class="relative">
                            <x-input-label for="reference-copy-button">
                                {{ __('Reference Code') }}
                            </x-input-label>
                            <x-text-input id="reference-copy-button" class="mt-2 w-full p-3.5" value="ABC1239090"
                                disabled readonly />
                            <button data-copy-to-clipboard-target="reference-copy-button"
                                data-tooltip-target="tooltip-copy-reference-copy-button"
                                class="mt-1 absolute end-2 top-4 translate-y-5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg p-2 inline-flex items-center justify-center">
                                <span id="default-icon">
                                    <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 18 20">
                                        <path
                                            d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
                                    </svg>
                                </span>
                                <span id="success-icon" class="hidden">
                                    <svg class="w-3.5 h-3.5 text-blue-700 dark:text-blue-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                    </svg>
                                </span>
                            </button>
                            <div id="tooltip-copy-reference-copy-button" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                <span id="default-tooltip-message">{{ __('Copy to clipboard') }}</span>
                                <span id="success-tooltip-message" class="hidden">{{ __('Copied!') }}</span>
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full mt-8 flex md:justify-start justify-center">
                        <img class="w-full md:w-96 object-contain" src="{{ asset('img/payme_code.jpg') }}" />
                    </div>
                </div>
            </div>
        @endif

        <!-- 已付款等待管理員確認狀態 -->
        @if (isset($hasPaidButNotConfirmed) && $hasPaidButNotConfirmed)
            <!-- 主要狀態卡片 -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-6 shadow-md border border-yellow-200 dark:border-yellow-700 mt-4">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/50 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-2">
                            {{ __('Payment Received - Awaiting Confirmation') }}
                        </h3>
                        <p class="text-yellow-700 dark:text-yellow-300">
                            {{ __('Your payment has been received and is being processed by our admin team. You will be notified once your booking is confirmed.') }}
                        </p>
                    </div>
                </div>

                <!-- 預訂進度追蹤 -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">{{ __('Booking Progress') }}</span>
                        <span class="text-xs text-yellow-600 dark:text-yellow-400">{{ __('Step 2 of 3') }}</span>
                    </div>
                    <div class="flex items-center">
                        <!-- Step 1: Payment -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="ml-2 text-xs text-green-700 dark:text-green-300 font-medium">{{ __('Payment') }}</span>
                        </div>
                        
                        <!-- Connection line -->
                        <div class="flex-1 h-0.5 bg-yellow-300 dark:bg-yellow-600 mx-2"></div>
                        
                        <!-- Step 2: Confirmation -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center animate-pulse">
                                <svg class="w-3 h-3 text-yellow-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="ml-2 text-xs text-yellow-700 dark:text-yellow-300 font-medium">{{ __('Confirmation') }}</span>
                        </div>
                        
                        <!-- Connection line -->
                        <div class="flex-1 h-0.5 bg-gray-300 dark:bg-gray-600 mx-2"></div>
                        
                        <!-- Step 3: Trip -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-5 5l-7-7"/>
                                </svg>
                            </div>
                            <span class="ml-2 text-xs text-gray-600 dark:text-gray-400 font-medium">{{ __('Trip') }}</span>
                        </div>
                    </div>
                </div>

                <!-- 處理時間信息 -->
                <div class="bg-yellow-100 dark:bg-yellow-900/30 rounded-lg p-3 border border-yellow-200 dark:border-yellow-600 mb-3">
                    <div class="flex items-center gap-2 text-sm text-yellow-800 dark:text-yellow-200 mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">{{ __('Expected Processing Time') }}</span>
                    </div>
                    <div class="text-xs text-yellow-700 dark:text-yellow-300 space-y-1">
                        <div>• {{ __('Business hours (9AM-6PM): 2-4 hours') }}</div>
                        <div>• {{ __('After hours/weekends: Next business day') }}</div>
                    </div>
                </div>

                <!-- 聯繫客服 -->
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03 8-9 8s9 3.582 9 8z"/>
                    </svg>
                    <span class="text-yellow-700 dark:text-yellow-300">{{ __('Need help?') }}</span>
                    <a href="tel:+85212345678" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                        {{ __('Contact Support') }}
                    </a>
                </div>
            </div>

            <!-- 你的預訂詳情卡片 -->
            @php
                $userJoin = $trip->joins->where('user_phone', $userPhone)->first();
                $userPayment = \App\Models\Payment::where('trip_id', $trip->id)->where('user_phone', $userPhone)->first();
            @endphp
            
            @if ($userJoin && $userPayment)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 mt-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('Your Booking Details') }}
                </h3>
                
                <div class="space-y-4">
                    <!-- 預訂信息 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('Reference Code') }}</div>
                            <div class="font-mono text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $userPayment->reference_code ?: 'Pending Assignment' }}
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('Payment Amount') }}</div>
                            <div class="font-semibold text-green-600 dark:text-green-400">
                                HK$ {{ number_format($userPayment->amount, 0) }}
                                @if($userPayment->passengers > 1)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        ({{ $userPayment->passengers }} {{ __('passengers') }})
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 接送地址 -->
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 border border-green-200 dark:border-green-700">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-xs font-medium text-green-800 dark:text-green-200 uppercase tracking-wide">
                                {{ __('Your Pickup Location') }}
                            </span>
                        </div>
                        <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                            {{ $userJoin->pickup_location ?: __('Location not set') }}
                        </div>
                    </div>

                    <!-- 溫馨提示 -->
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs text-blue-800 dark:text-blue-200">
                                <div class="font-medium mb-1">{{ __('What happens next?') }}</div>
                                <div class="space-y-1">
                                    <div>• {{ __('Admin will review and confirm your payment') }}</div>
                                    <div>• {{ __('You will receive notification once confirmed') }}</div>
                                    <div>• {{ __('Driver details shared 1-2 hours before departure') }}</div>
                                    <div>• {{ __('Be ready 15 minutes before pickup time') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif

        <!-- 多人預訂功能 -->
        @if (!$hasLeft && !$hasJoined && (!isset($hasPaidButNotConfirmed) || !$hasPaidButNotConfirmed))
            <!-- 多人預訂功能（適用於所有未加入的用戶） -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 mt-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('Group Booking') }}
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Book for multiple people') }}
                    </span>
                </div>

                <form id="group-booking-form" method="POST" action="{{ route('payment.create') }}">
                    @csrf
                    <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                    <input type="hidden" name="is_group_booking" value="1">
                    
                    <!-- 預訂人數選擇 -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Number of People') }}
                        </label>
                        
                        <!-- 可用槽位提示 -->
                        <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-blue-700 dark:text-blue-300">
                                    {{ __('Current Status') }}: {{ $currentPeople }}/{{ $trip->max_people }}
                                </span>
                                <span class="font-medium text-blue-800 dark:text-blue-200">
                                    @if($availableSlots > 0)
                                        {{ __('Available slots') }}: {{ $availableSlots }}
                                    @else
                                        <span class="text-red-600 dark:text-red-400">{{ __('Trip is full') }}</span>
                                    @endif
                                </span>
                            </div>
                            @if($availableSlots > 0 && $availableSlots < $trip->max_people)
                                <div class="mt-2 text-xs text-orange-600 dark:text-orange-400">
                                    {{ __('Limited slots available! Book quickly.') }}
                                </div>
                            @endif
                        </div>
                        <select id="people-count" name="people_count" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            @if($availableSlots > 0)
                                @for ($i = 1; $i <= min($availableSlots, 5); $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? __('person') : __('people') }}</option>
                                @endfor
                            @else
                                <option disabled>{{ __('No available slots') }}</option>
                            @endif
                        </select>
                    </div>

                    <!-- 動態乘客信息表單 -->
                    <div id="passengers-container" class="space-y-4 mb-6">
                        <!-- 第一個乘客 (主預訂人) -->
                        <div class="passenger-form border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-blue-50 dark:bg-blue-900/20">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Main Booker') }} ({{ __('Passenger 1') }})
                                </h4>
                                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium px-2 py-1 bg-blue-100 dark:bg-blue-800 rounded">
                                    {{ __('Primary Contact') }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('Name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="passengers[0][name]" required
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('Phone Number') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex">
                                        <select name="passengers[0][phone_country_code]" 
                                            class="rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                            <option value="+852">+852 (HK)</option>
                                            <option value="+86">+86 (CN)</option>
                                        </select>
                                        <input type="tel" name="passengers[0][phone]" required
                                            class="flex-1 rounded-r-md border-l-0 border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                            placeholder="12345678">
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('Pickup Location') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="hidden" name="passengers[0][pickup_location]" id="passenger-0-location" required>
                                        <button type="button" 
                                            class="passenger-location-btn w-full text-left px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-600 transition-colors"
                                            data-passenger="0"
                                            onclick="openMapForPassenger(0)">
                                            <div class="flex items-center justify-between">
                                                <span class="passenger-location-display text-gray-400 dark:text-gray-500 italic" id="passenger-0-display">
                                                    {{ __('Click to select pickup location on map') }}
                                                </span>
                                                <i class="material-icons text-gray-400 dark:text-gray-500">location_on</i>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 額外乘客模板 (將通過 JavaScript 動態生成) -->
                    </div>

                    <!-- 價格總覽 -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Price per person') }}:</span>
                                <span class="font-medium" id="price-per-person-display">HK$ {{ number_format($price, 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Number of people') }}:</span>
                                <span class="font-medium" id="people-display">1</span>
                            </div>
                            <div class="border-t border-gray-300 dark:border-gray-500 pt-2">
                                <div class="flex justify-between font-semibold">
                                    <span>{{ __('Total Amount') }}:</span>
                                    <span class="text-blue-600 dark:text-blue-400" id="total-amount">HK$ {{ number_format($price, 0) }}</span>
                                </div>
                            </div>
                            
                            <!-- 定價規則說明 -->
                            <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="text-xs text-blue-700 dark:text-blue-300">
                                    @if ($trip->type === 'golden')
                                        <strong>{{ __('Golden Hour') }}:</strong> {{ __('Fixed price HK$250 per person') }}
                                    @else
                                        <strong>{{ __('Normal Hour') }}:</strong>
                                        <div class="mt-1">
                                            • 1-3{{ __('people') }}: HK$275/{{ __('person') }}<br>
                                            • 4+{{ __('people') }}: HK$225/{{ __('person') }} ({{ __('HK$50 discount') }})
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 條款確認 -->
                    <div class="mb-6">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="group-booking-terms" required
                                class="mt-1 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="group-booking-terms" class="text-sm text-gray-700 dark:text-gray-300">
                                {{ __('I confirm that I have the consent of all passengers listed above to book this trip on their behalf. I understand the pricing rules and refund policies are managed by administrators.') }}
                            </label>
                        </div>
                    </div>

                    <!-- 提交按鈕 -->
                    @if($availableSlots > 0)
                        <button type="button" id="submit-group-booking"
                            class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white py-4 rounded-xl font-semibold text-lg transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ __('Book for Group') }} - <span id="total-amount-btn">HK$ {{ number_format($price, 0) }}</span>
                        </button>
                    @else
                        <div class="w-full bg-gray-400 text-white py-4 rounded-xl font-semibold text-lg text-center">
                            {{ __('Trip is Full - No Available Slots') }}
                        </div>
                    @endif
                </form>
            </div>
        @endif

        <!-- 操作按鈕 -->
        <div class="operations space-y-6 hidden">
            @if ($hasLeft)
                <div class="mt-8 flex justify-center text-center px-4">
                    <h2 class="text-md text-gray-900 dark:text-gray-300 font-black">
                        {{ __('You have left / was kicked from the trip.') }}
                    </h2>
                </div>
            @else
                @if (!$hasJoined && auth()->check() && (!isset($hasPaidButNotConfirmed) || !$hasPaidButNotConfirmed))
                    <!-- 註冊用戶的單人加入拼車表單 -->
                    @if($availableSlots > 0)
                        <button type="submit" id="join-trip-btn"
                            class="w-full mt-4 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white py-4 rounded-xl font-semibold text-lg transition shadow-md"
                        x-data=""
                        x-on:click.prevent="
                            const pickupLocation = document.getElementById('join-pickup-location').value;
                            if (!pickupLocation || pickupLocation.trim() === '') {
                                $dispatch('open-modal', 'location-required');
                                return false;
                            }
                            $dispatch('open-modal', 'confirm-join-trip')
                        ">
                        {{ __('Join') }} - HK$ {{ number_format($price, 0) }}
                    </button>
                    @else
                        <div class="w-full mt-4 bg-gray-400 text-white py-4 rounded-xl font-semibold text-lg text-center">
                            {{ __('Trip is Full') }}
                        </div>
                    @endif

                    <!-- 位置選擇提醒 Modal -->
                    <x-modal name="location-required" focusable>
                        <div class="p-8 text-center">
                            <div class="mb-4">
                                <div
                                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900/50">
                                    <i
                                        class="material-icons text-yellow-600 dark:text-yellow-400 text-2xl">location_on</i>
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Location Required
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">
                                Please select your pickup location before joining the trip!
                            </p>
                            <div class="flex justify-center space-x-3">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    {{ __('Cancel') }}
                                </x-secondary-button>
                                <x-primary-button
                                    x-on:click="$dispatch('close'); setTimeout(() => { document.getElementById('header-location-picker').scrollIntoView({ behavior: 'smooth' }); setTimeout(() => document.getElementById('header-location-picker').click(), 500); }, 100);">
                                    Select Location
                                </x-primary-button>
                            </div>
                        </div>
                    </x-modal>

                    <x-modal name="confirm-join-trip" focusable>
                        <form action="{{ route('payment.create') }}" method="POST" id="join-trip-form">
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                            <input type="hidden" name="amount" value="{{ $price }}">
                            <input type="hidden" name="user_phone" value="{{ $userPhone }}">
                            <input type="hidden" name="pickup_location" id="join-pickup-location"
                            value="{{ session('location') }}">
                            <div class="p-8 items-start">
                                <h2 class="text-lg text-gray-900 dark:text-gray-300 font-black">
                                    {{ __('Are you sure you want to join the trip?') }}
                                </h2>

                                <div
                                    class="mt-8 flow-root sm:mx-0 overflow-x-auto text-md text-gray-900 dark:text-gray-300">
                                    <span class="font-normal">
                                        {{ __('Full payment is required upon booking confirmation,') }}
                                    </span>
                                    <span class="text-red-500 dark:text-red-400 font-black">
                                        {{ __('which WILL NOT be refunded if you decided to leave the carpool.') }}
                                    </span>
                                </div>

                                <div
                                    class="mt-1 flow-root sm:mx-0 overflow-x-auto text-md text-gray-900 dark:text-gray-300">
                                    <span class="font-black">
                                        {{ __('Think carefully before joining.') }}
                                    </span>
                                </div>

                                <div
                                    class="mt-3 flow-root sm:mx-0 overflow-x-auto text-md text-gray-900 dark:text-gray-300">
                                    <span class="font-normal">
                                        {{ __('Required Amount: ') }}
                                    </span>
                                    <span class="font-black underline">
                                        {{ 'HK$' . number_format($price, 0) }}
                                    </span>
                                </div>
                                <div class="flex mt-6">
                                    <div class="flex items-center h-5">
                                        <input id="confirm-join" type="checkbox" value=""
                                            class="w-4 h-4 rounded bg-secondary dark:bg-secondary-dark border-gray-300 dark:border-gray-700 text-primary shadow-sm focus:ring-primary dark:focus:ring-primary-dark dark:focus:ring-offset-secondary-dark">
                                    </div>
                                    <div class="text-sm ms-2">
                                        <label for="confirm-join" class="font-normal text-gray-900 dark:text-gray-300">
                                            {{ __('Confirm') }} </label>
                                        <p id="private-checkbox-text"
                                            class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-300">
                                            {{ __('I have read and understand the terms.') }} </p>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <x-primary-button id="proceed-button"
                                        class="ms-3 disabled:bg-primary-dark dark:disabled:bg-primary-darker disabled:text-gray-300 dark:disabled:text-gray-500"
                                        disabled>
                                        {{ __('Proceed') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    </x-modal>
                @else
                    <!-- 離開拼車表單 - 所有用戶都可以離開 -->
                    <div class="mt-6">
                        <button
                            class="w-full bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white py-4 rounded-xl font-semibold transition shadow-md border border-red-300 dark:border-red-500"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-leave-trip')">
                            {{ __('Leave Carpool') }}
                        </button>

                        <x-modal name="confirm-leave-trip" focusable>
                            <form action="{{ route('trips.leave', $trip) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="p-8 items-start">
                                    <h2 class="text-lg text-gray-900 dark:text-gray-300 font-black">
                                        {{ __('Are you sure you want to leave the trip?') }}
                                    </h2>

                                    <div
                                        class="mt-8 flow-root sm:mx-0 overflow-x-auto text-md text-gray-900 dark:text-gray-300">
                                        <span class="text-red-500 dark:text-red-400 font-black">
                                            {{ __('The payment WILL NOT be refunded if you decided to leave the carpool.') }}
                                        </span>
                                    </div>

                                    <div
                                        class="mt-1 flow-root sm:mx-0 overflow-x-auto text-md text-gray-900 dark:text-gray-300">
                                        <span class="font-black">
                                            {{ __('Think carefully before leaving.') }}
                                        </span>
                                    </div>

                                    <div
                                        class="mt-3 flow-root sm:mx-0 overflow-x-auto text-md text-gray-900 dark:text-gray-300">
                                        <span class="font-normal">
                                            {{ __('Payment Amount: ') }}
                                        </span>
                                        <span class="font-black underline">
                                            {{ 'HK$' . number_format($price, 0) }}
                                        </span>
                                    </div>
                                    <div class="flex mt-6">
                                        <div class="flex items-center h-5">
                                            <input id="confirm-leave" type="checkbox" value=""
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        </div>
                                        <div class="text-sm ms-2">
                                            <label for="confirm"
                                                class="font-normal text-gray-900 dark:text-gray-300">
                                                {{ __('Confirm') }} </label>
                                            <p id="private-checkbox-text"
                                                class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-300">
                                                {{ __('I have read and understand the terms.') }} </p>
                                        </div>
                                    </div>
                                    <div class="mt-6 flex justify-end">
                                        <x-secondary-button x-on:click="$dispatch('close')">
                                            {{ __('Return') }}
                                        </x-secondary-button>

                                        <x-primary-button id="leave-button"
                                            class="ms-3 bg-red-500 dark:bg-red-600 hover:bg-red-500 dark:hover:bg-red-600 disabled:bg-red-700 dark:disabled:bg-red-900 disabled:text-gray-200 dark:disabled:text-gray-400 dark:text-white"
                                            disabled>
                                            {{ __('Leave') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                            </form>
                        </x-modal>
                    </div>
                @endif
            @endif
        </div>

        @if (!$hasLeft)
            <!-- Web Share API 分享按鈕 -->
            <div class="hidden operations">
                <div class="mt-4 space-y-3">
                    <!-- 主要分享按鈕 -->
                    <button id="share-btn"
                        class="w-full py-4 rounded-xl font-semibold flex items-center justify-center gap-3 transition shadow-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                        <span class="material-icons text-lg">share</span>
                        <span>{{ __('Share Trip') }}</span>
                    </button>
                    
                    <!-- 降級方案按鈕組 (僅在不支援 Web Share API 時顯示) -->
                    <div id="fallback-share-buttons" class="hidden space-y-2">
                        <button id="whatsapp-share-btn"
                            class="w-full py-3 rounded-lg font-medium flex items-center justify-center gap-3 transition shadow-sm text-white"
                            style="background-color: #25D366;">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.787"/>
                            </svg>
                            {{ __('Share via WhatsApp') }}
                        </button>
                        
                        <button id="copy-link-btn"
                            class="w-full py-3 rounded-lg font-medium flex items-center justify-center gap-3 transition shadow-sm text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                            <span class="material-icons text-lg">content_copy</span>
                            <span id="copy-text">{{ __('Copy Link') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>


<style>
    .overlay {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: fixed;
        background: #222;
        opacity: 50%;
    }

    .overlay__inner {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: absolute;
    }

    .overlay__content {
        left: 50%;
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .spinner {
        width: 75px;
        height: 75px;
        display: inline-block;
        border-width: 2px;
        border-color: rgba(255, 255, 255, 0.05);
        border-top-color: #fff;
        animation: spin 1s infinite linear;
        border-radius: 100%;
        border-style: solid;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    /* Web Share API 按鈕樣式 */
    #share-btn {
        position: relative;
        overflow: hidden;
    }

    #share-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    #share-btn:active::before {
        width: 300px;
        height: 300px;
    }

    /* 降級按鈕動畫 */
    #fallback-share-buttons {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, opacity 0.3s ease;
        opacity: 0;
    }

    #fallback-share-buttons:not(.hidden) {
        max-height: 200px;
        opacity: 1;
    }

    /* 觸摸設備優化 */
    @media (hover: none) {
        #share-btn, #whatsapp-share-btn, #copy-link-btn {
            transform: scale(1);
            transition: transform 0.1s ease, background-color 0.2s ease;
        }
        
        #share-btn:active, #whatsapp-share-btn:active, #copy-link-btn:active {
            transform: scale(0.96);
        }
    }

    /* 分享反饋動畫 */
    @keyframes shareSuccess {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .share-success {
        animation: shareSuccess 0.3s ease;
    }

    /* 多人預訂表單樣式 */
    .passenger-form {
        transition: all 0.3s ease;
    }
    
    .passenger-form:hover {
        border-color: #3b82f6;
        box-shadow: 0 0 0 1px #3b82f6;
    }
    
    .remove-passenger {
        opacity: 0.6;
        transition: opacity 0.2s ease;
    }
    
    .remove-passenger:hover {
        opacity: 1;
    }
    
    /* 價格總覽動畫 */
    @keyframes priceUpdate {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .price-updated {
        animation: priceUpdate 0.3s ease;
    }
    
    /* 乘客地址選擇按鈕樣式 */
    .passenger-location-btn {
        transition: all 0.2s ease;
        background: transparent;
    }
    
    .passenger-location-btn:hover {
        background-color: rgba(59, 130, 246, 0.05);
        border-color: rgba(59, 130, 246, 0.3);
    }
    
    .passenger-location-btn:focus {
        background-color: rgba(59, 130, 246, 0.05);
    }
    
    .passenger-location-btn.has-location {
        background-color: rgba(34, 197, 94, 0.05);
        border-color: rgba(34, 197, 94, 0.3);
    }
    
    .passenger-location-btn.has-location:hover {
        background-color: rgba(34, 197, 94, 0.1);
    }
</style>

<script type="module">
    $(document).ready(function() {
        
        // 保存當前表單狀態
        function saveFormState() {
            const formData = {};
            
            // 保存人數選擇
            formData.peopleCount = $('#people-count').val();
            
            // 保存所有乘客數據
            $('.passenger-form').each(function(index) {
                const passengerData = {};
                
                // 保存姓名
                const nameInput = $(this).find('input[name*="[name]"]');
                if (nameInput.length) {
                    passengerData.name = nameInput.val();
                }
                
                // 保存電話國碼
                const phoneCountryInput = $(this).find('select[name*="[phone_country_code]"]');
                if (phoneCountryInput.length) {
                    passengerData.phone_country_code = phoneCountryInput.val();
                }
                
                // 保存電話號碼
                const phoneInput = $(this).find('input[name*="[phone]"]');
                if (phoneInput.length) {
                    passengerData.phone = phoneInput.val();
                }
                
                // 保存地址
                const locationInput = $(this).find('input[name*="[pickup_location]"]');
                if (locationInput.length) {
                    passengerData.pickup_location = locationInput.val();
                }
                
                formData[`passenger_${index}`] = passengerData;
            });
            
            // 保存條款確認狀態
            formData.termsChecked = $('#group-booking-terms').is(':checked');
            
            localStorage.setItem('groupBookingFormData', JSON.stringify(formData));
            console.log('📝 表單狀態已保存');
        }

        // 更新指定乘客的位置信息
        window.updatePassengerLocation = function(passengerIndex, location) {
            console.log(`🎯 updatePassengerLocation 被調用:`, { passengerIndex, location });
            
            const hiddenInput = document.getElementById(`passenger-${passengerIndex}-location`);
            const displayElement = document.getElementById(`passenger-${passengerIndex}-display`);
            const locationBtn = document.querySelector(`.passenger-location-btn[data-passenger="${passengerIndex}"]`);
            
            console.log('🔍 DOM元素查找結果:', { 
                hiddenInput: !!hiddenInput, 
                displayElement: !!displayElement, 
                locationBtn: !!locationBtn 
            });
            
            if (hiddenInput && displayElement && location && location.formatted_address) {
                // 更新隱藏欄位的值
                hiddenInput.value = location.formatted_address;
                
                // 更新顯示文字
                displayElement.textContent = location.formatted_address;
                displayElement.classList.remove('text-gray-400', 'dark:text-gray-500', 'italic');
                displayElement.classList.add('text-gray-900', 'dark:text-gray-100');
                
                // 更新按鈕樣式，表示已選擇地址
                if (locationBtn) {
                    locationBtn.classList.add('has-location');
                    const icon = locationBtn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('text-gray-400', 'dark:text-gray-500');
                        icon.classList.add('text-green-600', 'dark:text-green-400');
                    }
                }
                
                // 更新localStorage中的表單狀態
                saveFormState();
                
                console.log(`✅ 已為乘客 ${passengerIndex} 設置地址:`, location.formatted_address);
            } else {
                console.warn('❌ 更新乘客地址失敗，缺少必要元素或數據');
            }
        };

        // Listen to location selection events
        window.addEventListener('location-selected', function(event) {
            const location = event.detail.location;
            if (location && location.formatted_address) {
                // 檢查是否有當前選擇的乘客索引
                const currentSelectingPassenger = localStorage.getItem('currentSelectingPassenger');
                
                if (currentSelectingPassenger !== null) {
                    // 這是為 group booking 中的乘客選擇地址
                    const passengerIndex = parseInt(currentSelectingPassenger);
                    updatePassengerLocation(passengerIndex, location);
                    
                    // 清理選擇狀態
                    localStorage.removeItem('currentSelectingPassenger');
                } else {
                    // 這是單人預訂的地址選擇
                    // Update displayed location (mobile version)
                    const displayElement = document.querySelector('#pickup_location_display span');
                    if (displayElement) {
                        displayElement.textContent = location.formatted_address;
                        displayElement.classList.remove('text-gray-400', 'dark:text-gray-500', 'italic');
                        displayElement.classList.add('text-gray-900', 'dark:text-gray-100');
                    }

                    // Update displayed location (desktop version)
                    const displayElementDesktop = document.querySelector('#pickup_location_display_desktop span');
                    if (displayElementDesktop) {
                        displayElementDesktop.textContent = location.formatted_address;
                        displayElementDesktop.classList.remove('text-gray-400', 'dark:text-gray-500', 'italic');
                        displayElementDesktop.classList.add('text-gray-900', 'dark:text-gray-100');
                    }

                    // Update hidden field in form
                    const hiddenField = document.getElementById('join-pickup-location');
                    if (hiddenField) {
                        hiddenField.value = location.formatted_address;
                    }

                    // Trigger custom event for Alpine.js to listen
                    window.dispatchEvent(new CustomEvent('location-updated', {
                        detail: {
                            address: location.formatted_address
                        }
                    }));

                    // Hide location selection prompt card
                    const locationAlert = document.querySelector('.bg-amber-50');
                    if (locationAlert) {
                        locationAlert.style.display = 'none';
                    }
                }
            }
        });

        $('#confirm-join').on('click', function() {
            if ($(this).is(':checked')) {
                $('#proceed-button').prop("disabled", false);
            } else {
                $('#proceed-button').prop("disabled", true);
            }
        });

        $('#confirm-leave').on('click', function() {
            if ($(this).is(':checked')) {
                $('#leave-button').prop("disabled", false);
            } else {
                $('#leave-button').prop("disabled", true);
            }
        });

        // Countdown timer function
        let timer = function(date) {
            let timer = Math.round(new Date(date).getTime() / 1000) - Math.round(new Date().getTime() /
                1000);
            let days, hours, minutes, seconds;

            // If more than 24 hours, don't show countdown
            if (timer > 86400) { // 86400 seconds = 24 hours
                $('#cd').hide();
                $('#waiting-driver').hide();
                $('.operations').show();
                $('.overlay').hide();
                return;
            }

            setInterval(function() {
                if (--timer < 0) {
                    timer = 0;
                    // Time's up! Show waiting for driver interface
                    $('#cd').hide();
                    $('#waiting-driver').show();
                    $('.operations').hide();
                    $('.overlay').hide();
                    return;
                }

                // 1 day left
                // If countdown is within 24 hours, show countdown
                if (timer <= 86400) {
                    $('#cd').show();
                    $('#waiting-driver').hide();

                    // 1 hour left --> disallow operations
                    if (timer <= 3600) {
                        $('.operations').hide();
                    } else {
                        $('.operations').show();
                    }

                    $('.overlay').hide();

                    hours = parseInt((timer / 60 / 60) % 24, 10);
                    minutes = parseInt((timer / 60) % 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    hours = hours < 10 ? "0" + hours : hours;
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    $('#cd-hours').html(hours);
                    $('#cd-minutes').html(minutes);
                    $('#cd-seconds').html(seconds);

                    // Change color based on remaining time
                    if (timer <= 3600) { // Within 1 hour
                        $('#cd').removeClass().addClass(
                            'bg-red-600 text-white rounded-xl p-4 text-center shadow-md mt-6');
                    } else {
                        $('#cd').removeClass().addClass(
                            'bg-orange-600 text-white rounded-xl p-4 text-center shadow-md mt-6'
                        );
                    }
                }
            }, 1000);
        };



        // Use countdown timer
        const plannedDepartureTime = new Date('{{ $trip->planned_departure_time }}');
        timer(plannedDepartureTime);

        // 檢測是否為手機設備
        function isMobile() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
                   window.innerWidth <= 768;
        }

        // 準備分享數據
        function getShareData() {
            const tripTitle = '{{ $trip->dropoff_location }}';
            const departureTime = '{{ $departureTime->format('Y-m-d H:i') }}';
            const price = 'HK$ {{ number_format($price, 0) }}';
            const currentPeople = '{{ $currentPeople }}';
            const maxPeople = '{{ $trip->max_people }}';

            // Get current URL, replace localhost with online domain if needed
            let shareUrl = window.location.href;
            if (shareUrl.includes('localhost') || shareUrl.includes('127.0.0.1')) {
                const appUrl = '{{ config('app.url') }}';
                if (appUrl && !appUrl.includes('localhost')) {
                    shareUrl = shareUrl.replace(/https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/, appUrl);
                } else {
                    shareUrl = shareUrl.replace(/https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/,
                        'https://your-actual-domain.com');
                }
            }

            const shareText = `🚗 ${tripTitle} 拼車邀請！

📍 目的地: ${tripTitle}
🕐 出發時間: ${departureTime}
💰 價格: ${price}/人
👥 目前人數: ${currentPeople}/${maxPeople}

點擊連結查看詳情並加入:
#拼車 #香港 #出行`;

            return {
                title: `${tripTitle} 拼車邀請`,
                text: shareText,
                url: shareUrl
            };
        }

        // 檢查 Web Share API 支援並初始化
        function initializeShare() {
            if (navigator.share) {
                // 支援 Web Share API，隱藏降級按鈕
                $('#fallback-share-buttons').addClass('hidden');
                console.log('✅ Web Share API 支援');
            } else {
                // 不支援 Web Share API，顯示降級按鈕
                $('#fallback-share-buttons').removeClass('hidden');
                console.log('❌ Web Share API 不支援，使用降級方案');
            }
        }

        // Web Share API 分享功能
        async function shareViaWebAPI() {
            const shareData = getShareData();
            
            try {
                // 檢查是否可以分享
                if (navigator.canShare && !navigator.canShare(shareData)) {
                    throw new Error('無法分享此內容');
                }
                
                await navigator.share(shareData);
                console.log('✅ 分享成功');
                
                // 顯示成功反饋
                showShareFeedback('success', '{{ __('Shared successfully!') }}');
                
            } catch (error) {
                console.log('❌ 分享失敗或取消:', error);
                
                if (error.name !== 'AbortError') {
                    // 不是用戶取消，顯示降級選項
                    showFallbackOptions();
                }
            }
        }

        // 顯示分享反饋
        function showShareFeedback(type, message) {
            const button = $('#share-btn');
            const originalContent = button.html();
            
            if (type === 'success') {
                button.html(`
                    <span class="material-icons text-lg">check_circle</span>
                    <span>${message}</span>
                `).removeClass('bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800')
                  .addClass('bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800');
            } else {
                button.html(`
                    <span class="material-icons text-lg">error</span>
                    <span>${message}</span>
                `).removeClass('bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800')
                  .addClass('bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800');
            }
            
            // 2秒後恢復
            setTimeout(() => {
                button.html(originalContent)
                      .removeClass('bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800')
                      .addClass('bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800');
            }, 2000);
        }

        // 顯示降級選項
        function showFallbackOptions() {
            $('#fallback-share-buttons').removeClass('hidden');
            showShareFeedback('error', '{{ __('Choose sharing method') }}');
            
            // 3秒後隱藏降級選項
            setTimeout(() => {
                if (navigator.share) {
                    $('#fallback-share-buttons').addClass('hidden');
                }
            }, 5000);
        }

        // 主要分享按鈕功能
        $('#share-btn').on('click', function() {
            if (navigator.share) {
                shareViaWebAPI();
            } else {
                // 直接顯示降級選項
                $('#fallback-share-buttons').toggleClass('hidden');
            }
        });

        // Copy link functionality (降級方案)
        $('#copy-link-btn').on('click', function() {
            const shareData = getShareData();
            const currentUrl = shareData.url;
            const copyText = $('#copy-text');
            const originalText = copyText.text();

            // Use modern Clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(currentUrl).then(function() {
                    // Successfully copied
                    copyText.text('{{ __('Copied!') }}');
                    
                    // 顯示成功反饋
                    const button = $('#copy-link-btn');
                    button.removeClass('bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600')
                          .addClass('bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-200');

                    // 隱藏降級按鈕
                    setTimeout(() => {
                        if (navigator.share) {
                            $('#fallback-share-buttons').addClass('hidden');
                        }
                    }, 1000);

                    // Restore after 2 seconds
                    setTimeout(function() {
                        copyText.text(originalText);
                        button.removeClass('bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-200')
                              .addClass('bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600');
                    }, 2000);
                }).catch(function(err) {
                    // Copy failed, use fallback method
                    fallbackCopyTextToClipboard(currentUrl, copyText, originalText);
                });
            } else {
                // Clipboard API not supported, use fallback method
                fallbackCopyTextToClipboard(currentUrl, copyText, originalText);
            }
        });

        // Fallback copy method (for older browsers)
        function fallbackCopyTextToClipboard(text, copyText, originalText) {
            const textArea = document.createElement("textarea");
            textArea.value = text;

            // Avoid zoom on iPhone
            textArea.style.position = "fixed";
            textArea.style.top = 0;
            textArea.style.left = 0;
            textArea.style.width = "2em";
            textArea.style.height = "2em";
            textArea.style.padding = 0;
            textArea.style.border = "none";
            textArea.style.outline = "none";
            textArea.style.boxShadow = "none";
            textArea.style.background = "transparent";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                const button = $('#copy-link-btn');
                
                if (successful) {
                    // Successfully copied
                    copyText.text('{{ __('Copied!') }}');
                    button.removeClass('bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30')
                          .addClass('bg-green-50 dark:bg-green-900/20');
                    button.find('div').removeClass('bg-blue-500').addClass('bg-green-500');

                    // 自動關閉分享面板
                    setTimeout(() => {
                        hideShareModal();
                    }, 1000);

                    // Restore after 2 seconds
                    setTimeout(function() {
                        copyText.text(originalText);
                        button.removeClass('bg-green-50 dark:bg-green-900/20')
                              .addClass('bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30');
                        button.find('div').removeClass('bg-green-500').addClass('bg-blue-500');
                    }, 2000);
                } else {
                    // Copy failed
                    copyText.text('{{ __('Copy Failed') }}');
                    button.removeClass('bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30')
                          .addClass('bg-red-50 dark:bg-red-900/20');
                    button.find('div').removeClass('bg-blue-500').addClass('bg-red-500');

                    setTimeout(function() {
                        copyText.text(originalText);
                        button.removeClass('bg-red-50 dark:bg-red-900/20')
                              .addClass('bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30');
                        button.find('div').removeClass('bg-red-500').addClass('bg-blue-500');
                    }, 2000);
                }
            } catch (err) {
                // Copy failed
                copyText.text('{{ __('Copy Failed') }}');
                const button = $('#copy-link-btn');
                button.removeClass('bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30')
                      .addClass('bg-red-50 dark:bg-red-900/20');
                button.find('div').removeClass('bg-blue-500').addClass('bg-red-500');

                setTimeout(function() {
                    copyText.text(originalText);
                    button.removeClass('bg-red-50 dark:bg-red-900/20')
                          .addClass('bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30');
                    button.find('div').removeClass('bg-red-500').addClass('bg-blue-500');
                }, 2000);
            }

            document.body.removeChild(textArea);
        }

        // WhatsApp share functionality (降級方案)
        $('#whatsapp-share-btn').on('click', function() {
            const shareData = getShareData();
            
            // 構建 WhatsApp 分享訊息
            const message = `${shareData.text}\n\n${shareData.url}`;
            const encodedMessage = encodeURIComponent(message);

            // 隱藏降級按鈕
            if (navigator.share) {
                $('#fallback-share-buttons').addClass('hidden');
            }

            // 手機版直接使用 WhatsApp URL scheme，桌面版使用 web.whatsapp.com
            let whatsappUrl;
            if (isMobile()) {
                whatsappUrl = `whatsapp://send?text=${encodedMessage}`;
            } else {
                whatsappUrl = `https://web.whatsapp.com/send?text=${encodedMessage}`;
            }

            // 打開 WhatsApp
            window.open(whatsappUrl, '_blank');
        });

        // 頁面加載時初始化
        $(document).ready(function() {
            initializeShare();
        });

        // === 多人預訂功能 ===
        let passengerCount = 1;
        const tripType = '{{ $trip->type }}';
        const basePricePerPerson = {{ $trip->price_per_person }};
        const fourPersonDiscount = {{ $trip->four_person_discount }};
        const availableSlots = {{ $availableSlots }};

        // 計算根據人數的價格（新定價邏輯）
        function calculatePricePerPerson(peopleCount) {
            if (tripType === 'golden') {
                return 250; // 黃金時段固定 250
            } else if (tripType === 'fixed') {
                return basePricePerPerson; // 固定價格
            } else {
                // 普通時段
                if (peopleCount >= 4) {
                    return 225; // 4人以上每人 225（275 - 50 折扣）
                } else {
                    return 275; // 1-3人每人 275
                }
            }
        }

        // 更新價格顯示
        function updatePriceDisplay() {
            const peopleCount = parseInt($('#people-count').val()) || 1;
            const pricePerPerson = calculatePricePerPerson(peopleCount);
            const totalAmount = peopleCount * pricePerPerson;
            
            $('#people-display').text(peopleCount);
            $('#price-per-person-display').text(`HK$ ${pricePerPerson.toLocaleString()}`);
            $('#total-amount').text(`HK$ ${totalAmount.toLocaleString()}`);
            $('#total-amount-btn').text(`HK$ ${totalAmount.toLocaleString()}`);
            
            // 為價格顯示添加動畫效果
            $('#total-amount, #price-per-person-display').addClass('price-updated');
            setTimeout(() => {
                $('#total-amount, #price-per-person-display').removeClass('price-updated');
            }, 300);
        }

        // 創建額外乘客表單
        function createPassengerForm(index) {
            return `
                <div class="passenger-form border border-gray-200 dark:border-gray-600 rounded-lg p-4" data-passenger="${index}">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Passenger') }} ${index + 1}
                        </h4>
                        <button type="button" class="remove-passenger text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" 
                            data-passenger="${index}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="passengers[${index}][name]" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Phone Number') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <select name="passengers[${index}][phone_country_code]" 
                                    class="rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="+852">+852 (HK)</option>
                                    <option value="+86">+86 (CN)</option>
                                </select>
                                <input type="tel" name="passengers[${index}][phone]" required
                                    class="flex-1 rounded-r-md border-l-0 border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    placeholder="12345678">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Pickup Location') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="hidden" name="passengers[${index}][pickup_location]" id="passenger-${index}-location" required>
                                <button type="button" 
                                    class="passenger-location-btn w-full text-left px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-600 transition-colors"
                                    data-passenger="${index}"
                                    onclick="openMapForPassenger(${index})">
                                    <div class="flex items-center justify-between">
                                        <span class="passenger-location-display text-gray-400 dark:text-gray-500 italic" id="passenger-${index}-display">
                                            {{ __('Click to select pickup location on map') }}
                                        </span>
                                        <i class="material-icons text-gray-400 dark:text-gray-500">location_on</i>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // 人數選擇變更事件
        $('#people-count').on('change', function() {
            const selectedCount = parseInt($(this).val()) || 1;
            const currentForms = $('.passenger-form').length;
            
            // 檢查是否超過可用槽位
            if (selectedCount > availableSlots) {
                alert(`{{ __('Cannot book for') }} ${selectedCount} {{ __('people. Only') }} ${availableSlots} {{ __('slots available.') }}`);
                $(this).val(Math.min(currentForms, availableSlots));
                return;
            }
            
            // 添加表單
            if (selectedCount > currentForms) {
                for (let i = currentForms; i < selectedCount; i++) {
                    $('#passengers-container').append(createPassengerForm(i));
                }
            } 
            // 移除多餘表單 (保留第一個)
            else if (selectedCount < currentForms) {
                $('.passenger-form').slice(selectedCount).remove();
            }
            
            updatePriceDisplay();
            
            // 保存表單狀態
            setTimeout(() => {
                saveFormState();
            }, 100);
        });

        // 移除乘客事件委託
        $(document).on('click', '.remove-passenger', function() {
            const passengerIndex = $(this).data('passenger');
            if (passengerIndex === 0) return; // 不允許移除主預訂人
            
            $(this).closest('.passenger-form').remove();
            
            // 重新編號
            $('.passenger-form').each(function(index) {
                $(this).attr('data-passenger', index);
                $(this).find('h4').text(index === 0 ? 
                    '{{ __('Main Booker') }} ({{ __('Passenger 1') }})' : 
                    `{{ __('Passenger') }} ${index + 1}`);
                
                // 更新 name 屬性和 id 屬性
                $(this).find('input, select').each(function() {
                    const name = $(this).attr('name');
                    if (name && name.includes('passengers[')) {
                        $(this).attr('name', name.replace(/passengers\[\d+\]/, `passengers[${index}]`));
                    }
                    
                    const id = $(this).attr('id');
                    if (id && id.includes('passenger-')) {
                        $(this).attr('id', id.replace(/passenger-\d+/, `passenger-${index}`));
                    }
                });
                
                // 更新地址選擇按鈕
                const locationBtn = $(this).find('.passenger-location-btn');
                if (locationBtn.length) {
                    locationBtn.attr('data-passenger', index);
                    locationBtn.attr('onclick', `openMapForPassenger(${index})`);
                }
                
                // 更新地址顯示元素
                const locationDisplay = $(this).find('.passenger-location-display');
                if (locationDisplay.length) {
                    locationDisplay.attr('id', `passenger-${index}-display`);
                }
                
                // 更新移除按鈕的 data-passenger
                $(this).find('.remove-passenger').attr('data-passenger', index);
            });
            
            // 更新人數選擇器
            const newCount = $('.passenger-form').length;
            $('#people-count').val(newCount);
            updatePriceDisplay();
            
            // 保存表單狀態
            setTimeout(() => {
                saveFormState();
            }, 100);
        });

        // 表單提交處理
        $('#submit-group-booking').on('click', function() {
            const form = $('#group-booking-form');
            const termsChecked = $('#group-booking-terms').is(':checked');
            
            if (!termsChecked) {
                alert('{{ __('Please agree to the terms and conditions.') }}');
                return;
            }
            
            // 驗證所有必填欄位
            let isValid = true;
            
            // 驗證基本欄位
            form.find('input[required], select[required]').each(function() {
                if (!$(this).val().trim()) {
                    $(this).focus();
                    alert('{{ __('Please fill in all required fields.') }}');
                    isValid = false;
                    return false;
                }
            });
            
            // 特別驗證所有乘客的地址是否已選擇
            if (isValid) {
                const peopleCount = parseInt($('#people-count').val()) || 1;
                for (let i = 0; i < peopleCount; i++) {
                    const locationInput = document.getElementById(`passenger-${i}-location`);
                    if (!locationInput || !locationInput.value.trim()) {
                        alert(`{{ __('Please select pickup location for passenger') }} ${i + 1}`);
                        // 滾動到相應的乘客表單
                        const passengerForm = document.querySelector(`[data-passenger="${i}"]`);
                        if (passengerForm) {
                            passengerForm.scrollIntoView({ behavior: 'smooth' });
                        }
                        isValid = false;
                        break;
                    }
                }
            }
            
            if (!isValid) return;
            
            // 計算總金額並添加到表單（全額付款）
            const peopleCount = parseInt($('#people-count').val()) || 1;
            const pricePerPerson = calculatePricePerPerson(peopleCount);
            const totalAmount = peopleCount * pricePerPerson;
            
            // 添加總金額到表單
            form.append(`<input type="hidden" name="total_amount" value="${totalAmount}">`);
            form.append(`<input type="hidden" name="price_per_person" value="${pricePerPerson}">`);
            
            // 清理保存的表單狀態
            localStorage.removeItem('groupBookingFormData');
            localStorage.removeItem('currentSelectingPassenger');
            
            // 提交表單
            form.submit();
        });

        // 初始化價格顯示
        updatePriceDisplay();

        // === 地圖選擇功能 ===
        
        // 恢復表單狀態
        function restoreFormState() {
            const savedData = localStorage.getItem('groupBookingFormData');
            if (!savedData) return;
            
            try {
                const formData = JSON.parse(savedData);
                console.log('📋 正在恢復表單狀態...');
                
                // 恢復人數選擇
                if (formData.peopleCount) {
                    $('#people-count').val(formData.peopleCount).trigger('change');
                }
                
                // 等待表單生成後恢復數據
                setTimeout(() => {
                    // 恢復各乘客數據
                    $('.passenger-form').each(function(index) {
                        const passengerData = formData[`passenger_${index}`];
                        if (!passengerData) return;
                        
                        // 恢復姓名
                        if (passengerData.name) {
                            $(this).find('input[name*="[name]"]').val(passengerData.name);
                        }
                        
                        // 恢復電話國碼
                        if (passengerData.phone_country_code) {
                            $(this).find('select[name*="[phone_country_code]"]').val(passengerData.phone_country_code);
                        }
                        
                        // 恢復電話號碼
                        if (passengerData.phone) {
                            $(this).find('input[name*="[phone]"]').val(passengerData.phone);
                        }
                        
                        // 恢復地址
                        if (passengerData.pickup_location) {
                            const locationInput = $(this).find('input[name*="[pickup_location]"]');
                            const locationDisplay = $(this).find('.passenger-location-display');
                            const locationBtn = $(this).find('.passenger-location-btn');
                            
                            if (locationInput.length && locationDisplay.length) {
                                locationInput.val(passengerData.pickup_location);
                                locationDisplay.text(passengerData.pickup_location);
                                locationDisplay.removeClass('text-gray-400 dark:text-gray-500 italic');
                                locationDisplay.addClass('text-gray-900 dark:text-gray-100');
                                
                                if (locationBtn.length) {
                                    locationBtn.addClass('has-location');
                                    const icon = locationBtn.find('i');
                                    if (icon.length) {
                                        icon.removeClass('text-gray-400 dark:text-gray-500');
                                        icon.addClass('text-green-600 dark:text-green-400');
                                    }
                                }
                            }
                        }
                    });
                    
                    // 恢復條款確認狀態
                    if (formData.termsChecked) {
                        $('#group-booking-terms').prop('checked', true);
                    }
                    
                    console.log('✅ 表單狀態恢復完成');
                }, 100);
                
            } catch (error) {
                console.error('恢復表單狀態失敗:', error);
                localStorage.removeItem('groupBookingFormData');
            }
        }
        
        // 為乘客選擇地圖位置
        window.openMapForPassenger = function(passengerIndex) {
            // 保存當前表單狀態
            saveFormState();
            
            // 設置當前選擇的乘客索引
            localStorage.setItem('currentSelectingPassenger', passengerIndex);
            
            // 打開地圖頁面
            const mapUrl = '{{ route('map') }}?passenger=' + passengerIndex + '&return=' + encodeURIComponent(window.location.pathname);
            window.location.href = mapUrl;
        };

        // 監聽來自地圖頁面的位置選擇事件
        window.addEventListener('passenger-location-selected', function(event) {
            const { passengerIndex, location } = event.detail;
            updatePassengerLocation(passengerIndex, location);
        });

        // 檢查URL參數，看是否從地圖頁面返回並帶有位置信息
        function checkMapReturnWithLocation() {
            const urlParams = new URLSearchParams(window.location.search);
            const returnedLocation = urlParams.get('location');
            const passengerIndex = urlParams.get('passenger');
            
            console.log('📍 檢查地圖返回參數:', { returnedLocation: !!returnedLocation, passengerIndex });
            
            if (returnedLocation && passengerIndex !== null) {
                try {
                    const location = JSON.parse(decodeURIComponent(returnedLocation));
                    console.log('📍 解析位置數據成功:', location);
                    
                    // 先恢復表單狀態，然後更新地址
                    setTimeout(() => {
                        console.log('📍 為乘客設置地址:', passengerIndex, location);
                        updatePassengerLocation(parseInt(passengerIndex), location);
                    }, 200);
                    
                    // 清理URL參數
                    const newUrl = window.location.pathname;
                    window.history.replaceState({}, document.title, newUrl);
                } catch (error) {
                    console.error('❌ 解析位置數據失敗:', error);
                }
            }
        }

        // 在表單數據發生變化時自動保存
        function setupAutoSave() {
            // 監聽表單輸入變化
            $(document).on('input change', '#group-booking-form input, #group-booking-form select', function() {
                // 延遲保存避免頻繁操作
                clearTimeout(window.autoSaveTimer);
                window.autoSaveTimer = setTimeout(() => {
                    saveFormState();
                }, 500);
            });
        }

        // 頁面載入時的初始化
        function initializeGroupBooking() {
            // 首先恢復表單狀態
            restoreFormState();
            
            // 然後檢查是否從地圖返回
            checkMapReturnWithLocation();
            
            // 設置自動保存
            setupAutoSave();
        }

        // 頁面載入時初始化
        initializeGroupBooking();
    });
</script>
