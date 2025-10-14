@section('Title', $trip->dropoff_location)
<x-app-layout>
    <x-slot name="header" class="transition">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                {{ __('Trip Details') }}
            </h2>
            <div class="flex">
                @if (session('guest_mode'))
                    <form action="{{ route('login') }}">
                        <button id="share-btn"
                            class="w-full -my-4 py-2 px-4 rounded-lg text-sm font-semibold flex items-center justify-center gap-3 transition shadow-md text-gray-100 dark:text-gray-300 bg-primary dark:bg-primary-dark hover:bg-primary-accent dark:hover:bg-primary">
                            <span class="material-icons text-sm">person</span>
                            <span class="text-sm">{{ __('Sign In') }}</span>
                        </button>
                    </form>
                @endif
                <button id="share-btn"
                    class="w-8 -my-4 py-1 ms-4 rounded-lg text-sm font-semibold flex items-center justify-center gap-3 transition shadow-md text-gray-100 dark:text-gray-300 bg-primary dark:bg-primary-dark hover:bg-primary-accent dark:hover:bg-primary"
                    x-data="" x-on:click.prevent="$dispatch('open-modal', 'share-method')">
                    <span class="material-icons text-sm">share</span>
                </button>

                <x-modal name="share-method" focusable>
                    <div class="p-6 w-full">
                        <h2 class="text-lg text-gray-900 dark:text-gray-300 font-black">
                            {{ __('Share Via') }}
                        </h2>
                        <button id="whatsapp-share-btn"
                            class="w-full mt-4 py-2 px-4 rounded-lg text-sm font-semibold flex items-center justify-center gap-3 transition text-gray-100 dark:text-gray-300 bg-green-600 hover:bg-green-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.787" />
                            </svg>
                            {{ __('WhatsApp') }}
                        </button>

                        <button id="copy-link-btn"
                            class="w-full mt-4 py-1 px-4 rounded-lg text-sm font-semibold flex items-center justify-center gap-3 transition text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                            <span class="material-icons text-lg">content_copy</span>
                            <span id="copy-text">{{ __('Copy Text') }}</span>
                        </button>
                    </div>
                </x-modal>

            </div>
        </div>
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
        <div
            class="bg-secondary dark:bg-secondary-accent rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex flex-wrap gap-2 mb-6 items-center">
                {{-- <span class="text-gray-600 dark:text-gray-300">{{ __('Status') }}</span> --}}
                <span
                    class="px-2 py-1 rounded-md text-xs
                        @if ($trip->trip_status === 'awaiting') bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300
                        @elseif($trip->trip_status === 'charging') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                        @elseif($trip->trip_status === 'departed') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif">
                    {{ ucfirst($trip->trip_status) }}
                </span>

                @if (
                    $trip->type === 'normal' &&
                        $currentPeople >= 3 &&
                        !$hasJoined &&
                        (!isset($hasPaidButNotConfirmed) || !$hasPaidButNotConfirmed))
                    <span
                        class="px-2 py-1 rounded-md text-xs bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 flex items-center gap-1">
                        <span class="material-icons text-xs">savings</span>
                        {{ __('4th person discount available - see pricing details') }}
                    </span>
                @endif
            </div>
            <!-- 路線顯示 - 響應式佈局：手機垂直，桌面水平 -->
            <div class="mb-4">
                @php
                    // 如果用戶已有預訂記錄，優先顯示數據庫中的實際地址
                    // 如果沒有預訂記錄，則顯示session中的臨時選擇
                    $userJoin = $trip->joins()->where('user_phone', $userPhone)->first();
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
                                <div class="w-3 h-3 bg-green-500 dark:bg-green-600 rounded-full mr-2 flex-shrink-0">
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                    {{ __('Pickup') }}</div>
                            </div>
                            <div id="pickup_location_display"
                                class="text-gray-800 dark:text-gray-200 font-medium leading-tight location-display">
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
                                <div class="w-3 h-3 bg-red-500 dark:bg-red-600 rounded-full mr-2 flex-shrink-0"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                    {{ __('Destination') }}</div>
                            </div>
                            <div class="text-gray-800 dark:text-gray-200 font-medium leading-tight location-display">
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
                            <div class="w-3 h-3 bg-green-500 dark:bg-green-600 rounded-full mr-2 flex-shrink-0"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                {{ __('Pickup') }}</div>
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
                            <div class="w-3 h-3 bg-red-500 dark:bg-red-600 rounded-full mr-2 flex-shrink-0"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                {{ __('Destination') }}</div>
                        </div>
                        <div class="text-gray-900 dark:text-gray-100 font-medium leading-tight location-display">
                            <span>{{ $trip->dropoff_location }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-start mt-6">
                <div>
                    <div class="text-3xl font-bold text-primary-accent dark:text-primary">
                        {{ $departureTime->format('H:i') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $departureTime->format('Y-m-d') }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-primary-accent dark:text-primary">
                        HK$ {{ number_format($userFee, 0) }}
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
                        class="font-semibold text-gray-800 dark:text-gray-300">{{ $currentPeople }}/{{ $trip->max_people }}</span>
                </div>
            </div>
        </div>

        <!-- 司機資訊區域 - 只在用戶已加入行程後顯示 -->
        @if (($hasJoined || (isset($hasPaidButNotConfirmed) && $hasPaidButNotConfirmed)) && $assignedDriver)
            <div
                class="bg-secondary dark:bg-secondary-accent rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 mt-4">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary dark:text-primary-dark" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('Driver Information') }}
                    </h3>

                    @if ($trip->trip_status == 'departed')
                        <span
                            class="px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full animate-pulse">
                            {{ __('Trip Started') }}
                        </span>
                    @elseif($trip->trip_status == 'awaiting')
                        <span
                            class="px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full">
                            {{ __('Assigned') }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-4 mb-4">
                    <!-- 司機頭像 -->
                    <div
                        class="w-16 h-16 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 dark:text-blue-300 font-semibold text-xl">
                            {{ strtoupper(substr($assignedDriver->username, 0, 1)) }}
                        </span>
                    </div>

                    <!-- 司機基本資訊 -->
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 text-xl">
                            {{ $assignedDriver->username }}
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 mb-2">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                                {{ $assignedDriver->email }}
                            </span>
                        </p>
                        @if ($assignedDriver->phone)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $assignedDriver->phone }}
                                </span>
                            </p>
                        @endif
                    </div>

                    <!-- 聯絡司機按鈕 -->
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

                <!-- 司機狀態和提醒 -->
                <div
                    class="bg-primary-opaque dark:bg-primary-opaque-dark rounded-lg p-4 mt-6 border border-primary dark:border-primary-dark">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 text-primary mt-0.5">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900 dark:text-gray-100 text-sm mb-1">
                                {{ __('Driver has been assigned to this trip') }}
                            </div>
                            <div class="text-gray-700 dark:text-gray-400 text-sm">
                                {{ __('You can contact the driver directly using the buttons above when the trip time approaches.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-6">
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
        @elseif ($hasJoined || (isset($hasPaidButNotConfirmed) && $hasPaidButNotConfirmed))
            <div
                class="bg-yellow-50 dark:bg-yellow-900/50 rounded-xl p-6 shadow-md border border-yellow-200 dark:border-yellow-800 mt-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-1">
                            {{ __('Driver Assignment Pending') }}
                        </h3>
                        <p class="text-yellow-700 dark:text-yellow-300 text-sm">
                            {{ __('A driver will be assigned to this trip anytime before its departure.') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- 成員列表 -->
        @if ($trip->joins->isNotEmpty())
            <div
                class="bg-secondary dark:bg-secondary-accent rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 mt-4">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">{{ __('Members') }}</h3>
                @foreach ($trip->joins as $join)
                    <div
                        class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                @if ($join->user && $join->user->username)
                                    <span class="text-blue-600 dark:text-blue-300 font-semibold text-sm">
                                        {{ strtoupper(substr($join->user->username, 0, 1)) }}
                                    </span>
                                @else
                                    <span class="text-blue-600 dark:text-blue-300 font-semibold text-sm">
                                        {{ strtoupper(substr($join->user_phone, -1)) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    @if ($join->user && $join->user->username)
                                        {{ $join->user->username }}
                                    @else
                                        {{ __('Guest User') }}
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Joined') }} {{ $join->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        <div class="text-sm">
                            @if ($join->user_phone === $userPhone)
                                <span
                                    class="px-2 py-1 bg-primary-opaque dark:bg-primary-opaque-dark text-gray-500 dark:text-gray-300 rounded text-xs">
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

        <!-- 邀請同行成員功能 - 只對已加入且為群組預訂的用戶顯示 -->
        {{-- @php
            $userPayment = \App\Models\Payment::where('trip_id', $trip->id)->where('user_phone', $userPhone)->first();
            $isGroupBooking = $userPayment && $userPayment->type === 'group';
        @endphp --}}
        @if ($showInvitationCode)
            <div
                class="bg-secondary dark:bg-secondary-accent rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700 mt-4">
                <!-- 標題 -->
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-icons text-primary dark:text-primary-dark text-xl">group_add</span>
                    <h3 class="text-base font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Invite Trip Members') }}
                    </h3>
                </div>

                <!-- 邀請代碼卡片 -->
                <div
                    class="bg-primary-opaque dark:bg-primary-opaque-dark rounded-lg p-4 my-6 border border-primary dark:border-primary-dark">
                    <div class="text-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ __('Invitation Code') }}</div>
                        <div
                            class="font-mono text-2xl font-bold text-gray-900 dark:text-gray-200 tracking-widest mb-3">
                            {{ $trip->invitation_code }}
                        </div>
                        <button id="copy-invitation-code"
                            class="w-full bg-primary dark:bg-primary-dark text-gray-100 dark:text-gray-200 py-2 px-4 rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition-colors"
                            data-code="{{ $trip->invitation_code }}">
                            <span class="material-icons text-sm">content_copy</span>
                            <span class="copy-text">{{ __('Copy Code') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- 行程滿員提示 -->
        @if ($availableSlots <= 0 && !$hasJoined && (!isset($hasPaidButNotConfirmed) || !$hasPaidButNotConfirmed))
            <div
                class="bg-red-50 dark:bg-red-900/20 rounded-xl p-6 shadow-md border border-red-200 dark:border-red-700 mt-4">
                <div class="flex items-center gap-4 text-center justify-center">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center">
                        <span class="material-icons text-red-600 dark:text-red-400">event_busy</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-2">
                            {{ __('Trip is Full') }}
                        </h3>
                        <p class="text-red-600 dark:text-red-300">
                            {{ __('This trip has reached its maximum capacity. Please check other available trips.') }}
                        </p>
                    </div>
                </div>
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
            {{-- <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700">
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
            </div> --}}

            <!-- 安全和聯絡功能 -->
            <div
                class="bg-secondary dark:bg-secondary-accent rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 text-center">
                    {{ __('Emergency Contact') }}</h3>
                <div class="space-y-3">
                    <!-- 香港緊急電話 -->
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6">
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
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6">
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
            <div
                class="bg-primary-opaque dark:bg-primary-opaque-dark rounded-xl p-4 border border-primary dark:border-primary-dark">
                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 text-primary dark:text-primary-dark mt-0.5">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 dark:text-gray-100 text-sm">
                            {{ __('Trip Started') }}
                        </div>
                        <div class="text-gray-700 dark:text-gray-400 text-sm mt-1">
                            {{ __('Please wait for the driver to contact you. Make sure your phone is accessible.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- @if ($hasJoined)
            <div
                class="bg-secondary dark:bg-secondary-accent rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 mt-4">
                <div class="flex flex-col items-center gap-3">
                    <div class="text-primary mt-0.5">
                        <i class="fas fa-exclamation-circle fa-2x" style="font-size: 2.5rem;"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-bold text-gray-700 dark:text-gray-200 text-xl text-center my-4">
                            {{ __('Reminder') }}
                        </div>
                        <div class="text-gray-700 dark:text-gray-200 text-sm mt-1">
                            {{ __('Complete your payment to secure your spot! Payment confirmation required before departure.') }}
                        </div>
                        <div class="text-gray-700 dark:text-gray-200 text-md mt-4 font-bold">
                            {{ __('Full Amount:') . " HK$" . number_format($userFee, 0) }}
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
        @endif --}}

        <!-- 已付款等待管理員確認狀態 -->
        {{-- @if (isset($hasPaidButNotConfirmed) && $hasPaidButNotConfirmed)
            <!-- 主要狀態卡片 -->
            <div
                class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-6 shadow-md border border-yellow-200 dark:border-yellow-700 mt-4">
                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/50 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                        <span
                            class="text-sm font-medium text-yellow-800 dark:text-yellow-200">{{ __('Booking Progress') }}</span>
                        <span class="text-xs text-yellow-600 dark:text-yellow-400">{{ __('Step 2 of 3') }}</span>
                    </div>
                    <div class="flex items-center">
                        <!-- Step 1: Payment -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span
                                class="ml-2 text-xs text-green-700 dark:text-green-300 font-medium">{{ __('Payment') }}</span>
                        </div>

                        <!-- Connection line -->
                        <div class="flex-1 h-0.5 bg-yellow-300 dark:bg-yellow-600 mx-2"></div>

                        <!-- Step 2: Confirmation -->
                        <div class="flex items-center">
                            <div
                                class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center animate-pulse">
                                <svg class="w-3 h-3 text-yellow-800" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span
                                class="ml-2 text-xs text-yellow-700 dark:text-yellow-300 font-medium">{{ __('Confirmation') }}</span>
                        </div>

                        <!-- Connection line -->
                        <div class="flex-1 h-0.5 bg-gray-300 dark:bg-gray-600 mx-2"></div>

                        <!-- Step 3: Trip -->
                        <div class="flex items-center">
                            <div
                                class="w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-gray-600 dark:text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 14l-5 5l-7-7" />
                                </svg>
                            </div>
                            <span
                                class="ml-2 text-xs text-gray-600 dark:text-gray-400 font-medium">{{ __('Trip') }}</span>
                        </div>
                    </div>
                </div>

                <!-- 處理時間信息 -->
                <div
                    class="bg-yellow-100 dark:bg-yellow-900/30 rounded-lg p-3 border border-yellow-200 dark:border-yellow-600 mb-3">
                    <div class="flex items-center gap-2 text-sm text-yellow-800 dark:text-yellow-200 mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03 8-9 8s9 3.582 9 8z" />
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
                $userPayment = \App\Models\Payment::where('trip_id', $trip->id)
                    ->where('user_phone', $userPhone)
                    ->first();
            @endphp

            @if ($userJoin && $userPayment)
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 mt-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('Your Booking Details') }}
                    </h3>

                    <div class="space-y-4">
                        <!-- 預訂信息 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('Reference Code') }}
                                </div>
                                <div class="font-mono text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $userPayment->reference_code ?: 'Pending Assignment' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('Payment Amount') }}
                                </div>
                                <div class="font-semibold text-green-600 dark:text-green-400">
                                    HK$ {{ number_format($userPayment->amount, 0) }}
                                    @if ($userPayment->passengers > 1)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            ({{ $userPayment->passengers }} {{ __('passengers') }})
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- 接送地址 -->
                        <div
                            class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 border border-green-200 dark:border-green-700">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span
                                    class="text-xs font-medium text-green-800 dark:text-green-200 uppercase tracking-wide">
                                    {{ __('Your Pickup Location') }}
                                </span>
                            </div>
                            <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                                {{ $userJoin->pickup_location ?: __('Location not set') }}
                            </div>
                        </div>

                        <!-- 溫馨提示 -->
                        <div
                            class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
        @endif --}}
        <!-- 預訂功能 -->
        @if (!$hasJoined && (!isset($hasPaidButNotConfirmed) || !$hasPaidButNotConfirmed) && $availableSlots > 0)
            <!-- 預訂功能（支援個人或多人預訂） -->
            <div
                class="bg-secondary dark:bg-secondary-accent rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 mt-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('Book This Trip') }}
                    </h3>
                </div>

                <form id="group-booking-form" method="POST" action="{{ route('payment.create') }}">
                    @csrf
                    <input type="hidden" name="trip_id" value="{{ $trip->id }}">

                    <!-- 預訂人數選擇 -->
                    <div class="mb-6">
                        @if ($availableSlots > 0)
                            <!-- 可用槽位提示 -->
                            <div
                                class="mb-3 p-3 bg-primary-opaque dark:bg-primary-opaque-dark border border-primary-accent dark:border-primary rounded-lg">
                                <span class="text-gray-700 dark:text-gray-300 text-sm">
                                    {{ __('Current Available Slots') }}:
                                </span>
                                <span class="underline text-gray-700 dark:text-gray-200 ms-2 text-lg">
                                    {{ $availableSlots }}
                                </span>
                                @if ($availableSlots < $trip->max_people)
                                    <div class="mt-2 text-xs text-orange-600 dark:text-orange-400">
                                        {{ __('Limited slots available! Book quickly.') }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <div
                                class="flex justify-center mb-3 p-3 border border-red-500 dark:border-red-600 rounded-lg">
                                <span class="text-red-500 dark:text-red-600 text-lg font-bold">
                                    {{ __('Trip is full!') }}
                                </span>
                            </div>
                        @endif
                        <select id="people-count" name="people_count"
                            class="mt-2 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-secondary-dark dark:text-gray-300 focus:border-primary dark:focus:border-primary-dark focus:ring-primary dark:focus:ring-primary-dark">
                            @if ($availableSlots > 0)
                                @for ($i = 1; $i <= min($availableSlots, 5); $i++)
                                    <option value="{{ $i }}">{{ $i }}
                                        {{ $i == 1 ? __('person') : __('people') }}</option>
                                @endfor
                            @else
                                <option disabled>{{ __('No available slots') }}</option>
                            @endif
                        </select>
                    </div>

                    <!-- 動態乘客信息表單 -->
                    <div id="passengers-container" class="space-y-4 mb-6">
                        <!-- 第一個乘客 (主預訂人) -->
                        <div
                            class="passenger-form border border-primary-accent dark:border-primary hover:border-primary rounded-lg p-4 bg-primary-opaque dark:bg-primary-opaque-dark">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-700 dark:text-gray-200">
                                    {{ __('Main Booker') }} ({{ __('Passenger 1') }})
                                </h4>
                                <span
                                    class="text-xs text-gray-100 dark:text-gray-200 font-medium px-2 py-1 bg-primary-accent dark:bg-primary rounded">
                                    {{ __('Primary Contact') }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('Name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <x-text-input name="passengers[0][name]" required
                                        class="w-full border-gray-300 dark:border-gray-700" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('Phone Number') }} <span class="text-red-500">*</span>
                                    </label>
                                    @php
                                        $prefixes = ['+852', '+86']; // Prefixes to remove
                                        $_userPhone = str_replace($prefixes, '', $userPhone);
                                        if ($_userPhone == $userPhone) {
                                            $_userPhone = $userPhone;
                                        }
                                        $code = strpos($userPhone, '+852') !== false ? '+852' : '+86';
                                    @endphp
                                    <div class="flex">
                                        <select name="passengers[0][phone_country_code]"
                                            class="rounded-l-md border-gray-300 dark:border-gray-700 bg-secondary dark:bg-secondary-dark dark:text-gray-300 focus:border-primary dark:focus:border-primary-dark focus:ring-primary dark:focus:ring-primary-dark shadow-sm">
                                            <option value="+852"
                                                {{ $code == '+852' || $userPhone == null ? 'selected' : '' }}>+852 (HK)
                                            </option>
                                            <option value="+86" {{ $code == '+86' ? 'selected' : '' }}>+86 (CN)
                                            </option>
                                        </select>
                                        <x-text-input type="tel" name="passengers[0][phone]" required
                                            class="border-gray-300 dark:border-gray-700 block w-full rounded-l-none border-l-0"
                                            placeholder="12345678" value="{{ $_userPhone }}" />
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('Pickup Location') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="hidden" name="passengers[0][pickup_location]"
                                            id="passenger-0-location" required>
                                        <button type="button"
                                            class="passenger-location-btn w-full text-left px-3 py-2 text-sm bg-secondary dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-md dark:bg-secondary-dark dark:text-gray-300 focus:border-primary dark:focus:border-primary-dark focus:ring-primary dark:focus:ring-primary-dark shadow-sm transition-colors"
                                            data-passenger="0" onclick="openMapForPassenger(0)">
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="passenger-location-display text-gray-400 dark:text-gray-500 italic"
                                                    id="passenger-0-display">
                                                    {{ __('Click to select pickup location on map') }}
                                                </span>
                                                <i
                                                    class="material-icons text-gray-400 dark:text-gray-500">location_on</i>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 額外乘客模板 (將通過 JavaScript 動態生成) -->
                    </div>

                    <!-- 優惠券區域 -->
                    <div
                        class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4 mb-6 border border-amber-200 dark:border-amber-800">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="material-icons text-amber-600 dark:text-amber-400">local_offer</span>
                            <h4 class="font-medium text-gray-700 dark:text-gray-200">{{ __('Coupon Code') }}</h4>
                        </div>
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <input type="text" id="coupon-code" name="coupon_code"
                                    oninput="let p = this.selectionStart; this.value = this.value.toUpperCase();this.setSelectionRange(p, p);"
                                    placeholder="{{ __('Enter coupon code') }}"
                                    class="w-full rounded-md border-gray-400 dark:border-gray-700 bg-secondary dark:bg-secondary-dark dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-600 focus:ring-amber-500 dark:focus:ring-amber-600 text-sm">
                            </div>
                            <button type="button" id="apply-coupon"
                                class="px-4 py-2 bg-amber-600 hover:bg-amber-700 dark:bg-amber-700 dark:hover:bg-amber-800 text-white rounded-lg font-medium text-sm transition">
                                {{ __('Apply') }}
                            </button>
                        </div>

                        <!-- 優惠券狀態顯示 -->
                        <div id="coupon-status" class="mt-3">
                            <!-- 成功狀態 -->
                            <div id="coupon-success"
                                class="hidden p-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded text-sm">
                                <div class="flex items-center gap-2 text-green-700 dark:text-green-300">
                                    <span class="material-icons text-sm">check_circle</span>
                                    <span id="coupon-success-text"></span>
                                </div>
                            </div>
                            <!-- 錯誤狀態 -->
                            <div id="coupon-error"
                                class="hidden p-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded text-sm">
                                <div class="flex items-center gap-2 text-red-700 dark:text-red-300">
                                    <span class="material-icons text-sm">error</span>
                                    <span id="coupon-error-text"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 價格總覽 -->
                    <div class="bg-gray-100 dark:bg-neutral-800 rounded-lg p-6 mb-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700 dark:text-gray-300">{{ __('Price per person') }}:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-200"
                                    id="price-per-person-display">HK$
                                    {{ number_format($userFee, 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700 dark:text-gray-300">{{ __('Number of people') }}:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-200"
                                    id="people-display">1</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700 dark:text-gray-300">{{ __('Subtotal') }}:</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-200" id="subtotal-amount">HK$
                                    {{ number_format($userFee, 0) }}</span>
                            </div>
                            <!-- 優惠券折扣行 (僅在套用優惠券時顯示) -->
                            <div id="coupon-discount-row" class="hidden flex justify-between text-sm">
                                <span class="text-green-700 dark:text-green-600">{{ __('Coupon Discount') }}:</span>
                                <span class="font-semibold text-green-600 dark:text-green-500"
                                    id="coupon-discount-amount">-HK$ 0</span>
                            </div>
                            <div class="border-t border-gray-300 dark:border-gray-500 pt-2">
                                <div class="flex justify-between font-bold text-gray-900 dark:text-gray-200">
                                    <span>{{ __('Total Amount') }}:</span>
                                    <span class="font-bold text-primary-accent dark:text-primary"
                                        id="total-amount">HK$
                                        {{ number_format($userFee, 0) }}</span>
                                </div>
                            </div>

                            <!-- 定價規則說明 -->
                            <div class="mt-3 p-3 bg-primary-opaque dark:bg-primary-opaque-dark rounded-lg">
                                <div class="text-xs text-gray-700 dark:text-gray-300">
                                    @if ($trip->type === 'golden')
                                        <strong>{{ __('Golden Hour') }}:</strong>
                                        {{ __('Fixed price HK$250 per person') }}
                                    @else
                                        <strong>{{ __('Normal Hour') }}:</strong>
                                        <div class="mt-1">
                                            • 1-3 {{ __('people') }}: HK$275/{{ __('person') }}<br>
                                            • 4+ {{ __('people') }}: HK$225/{{ __('person') }}
                                            ({{ __('HK$50 discount') }})
                                        </div>

                                        @if ($currentPeople >= 3)
                                            <!-- 四人優惠退款政策提醒 -->
                                            <div
                                                class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                                <div class="flex items-start gap-2">
                                                    <span
                                                        class="material-icons text-amber-600 dark:text-amber-400 text-sm mt-0.5">info</span>
                                                    <div class="text-xs text-amber-800 dark:text-amber-200">
                                                        <div class="font-medium mb-1">
                                                            {{ __('4-Person Discount Policy') }}</div>
                                                        <div class="leading-relaxed space-y-1">
                                                            <div>•
                                                                {{ __('All passengers must pay full price (HK$275) initially') }}
                                                            </div>
                                                            <div>•
                                                                {{ __('HK$50 refund per person processed after trip deadline') }}
                                                            </div>
                                                            <div>•
                                                                {{ __('Refunds only if 4+ people confirmed and no cancellations') }}
                                                            </div>
                                                            <div>•
                                                                {{ __('Admin will handle refunds offline within 48 hours post-deadline') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 條款確認 -->
                    <div class="mb-6">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="group-booking-terms" required
                                class="rounded bg-secondary dark:bg-secondary-dark border-gray-300 dark:border-gray-700 text-primary shadow-sm focus:ring-primary dark:focus:ring-primary-dark dark:focus:ring-offset-secondary-dark">
                            <label for="group-booking-terms" class="text-sm text-gray-700 dark:text-gray-300">
                                {{ __('I confirm that I have the consent of all passengers listed above to book this trip on their behalves.') }}
                            </label>
                        </div>
                    </div>

                    <!-- 提交按鈕 -->
                    @if ($availableSlots > 0)
                        <button type="button" id="submit-group-booking"
                            class="w-full bg-primary dark:bg-primary-dark hover:bg-primary-accent dark:hover:bg-primary text-gray-100 dark:text-gray-300 py-4 rounded-lg font-semibold text-lg transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ __('Book Now') }} - <span id="total-amount-btn">HK$
                                {{ number_format($userFee, 0) }}</span>
                        </button>
                    @else
                        <div class="w-full bg-gray-400 text-white py-4 rounded-xl font-semibold text-lg text-center">
                            {{ __('Trip is Full - No Available Slots') }}
                        </div>
                    @endif
                </form>
            </div>
        @endif

        <!-- 離開拼車功能 - 只對已加入的用戶顯示 -->
        @if ($hasJoined && !$hasLeft)
            <div class="mt-6">
                <button
                    class="w-full bg-red-600 hover:bg-red-500 dark:bg-red-700 dark:hover:bg-red-600 text-gray-100 dark:text-gray-200 py-4 rounded-xl font-semibold transition shadow-md"
                    x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-leave-trip')">
                    {{ __('Leave Carpool') }}
                </button>

                <x-modal name="confirm-leave-trip" focusable>
                    <form action="{{ route('trips.leave', $trip) }}">
                        @csrf
                        <div class="p-8 items-start">
                            <h2 class="text-xl text-gray-900 dark:text-gray-300 font-black">
                                {{ __('Are you sure you want to leave the trip?') }}
                            </h2>

                            <div
                                class="mt-6 flow-root sm:mx-0 overflow-x-auto text-md text-gray-900 dark:text-gray-300">
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

                            @if ($price != null)
                                <div
                                    class="mt-3 flow-root sm:mx-0 overflow-x-auto text-md text-gray-900 dark:text-gray-300">
                                    <span class="font-normal">
                                        {{ __('Payment Amount: ') }}
                                    </span>
                                    <span class="font-black underline">
                                        {{ 'HK$' . number_format($price, 0) }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex mt-6">
                                <div class="flex items-center h-5">
                                    <input id="confirm-leave" type="checkbox" value=""
                                        class="w-4 h-4 rounded bg-secondary dark:bg-secondary-dark border-gray-300 dark:border-gray-700 text-primary shadow-sm focus:ring-primary dark:focus:ring-primary-dark dark:focus:ring-offset-secondary-dark">
                                </div>
                                <div class="text-sm ms-2">
                                    <label for="confirm" class="font-normal text-gray-900 dark:text-gray-300">
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

        <!-- 已離開用戶的提示訊息 -->
        @if ($hasLeft)
            <div class="mt-8 flex justify-center text-center px-4">
                <h2 class="text-md text-gray-900 dark:text-gray-300 font-black">
                    {{ __('You have left / was kicked from the trip.') }}
                </h2>
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

        #share-btn,
        #whatsapp-share-btn,
        #copy-link-btn {
            transform: scale(1);
            transition: transform 0.1s ease, background-color 0.2s ease;
        }

        #share-btn:active,
        #whatsapp-share-btn:active,
        #copy-link-btn:active {
            transform: scale(0.96);
        }
    }

    /* 分享反饋動畫 */
    @keyframes shareSuccess {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .share-success {
        animation: shareSuccess 0.3s ease;
    }

    /* 多人預訂表單樣式 */
    .passenger-form {
        transition: all 0.3s ease;
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
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .price-updated {
        animation: priceUpdate 0.3s ease;
    }

    /* 乘客地址選擇按鈕樣式 */
    .passenger-location-btn {
        transition: all 0.2s ease;
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

    /* 滾動行為優化 */
    html {
        scroll-behavior: smooth;
    }

    /* 地址選擇成功時的動畫 */
    .location-selected-animation {
        animation: locationSelectedPulse 0.6s ease-in-out;
    }

    @keyframes locationSelectedPulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
        }

        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(34, 197, 94, 0.1);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
        }
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

        // 保存當前滾動位置
        function saveScrollPosition() {
            const scrollY = window.scrollY || window.pageYOffset || document.documentElement.scrollTop;
            localStorage.setItem('tripShowScrollPosition', scrollY.toString());
            console.log('📍 滾動位置已保存:', scrollY);
        }

        // 恢復滾動位置
        function restoreScrollPosition() {
            const savedScrollY = localStorage.getItem('tripShowScrollPosition');
            if (savedScrollY !== null) {
                const scrollPosition = parseInt(savedScrollY, 10);

                // 使用平滑滾動效果
                window.scrollTo({
                    top: scrollPosition,
                    behavior: 'smooth'
                });

                console.log('📍 滾動位置已恢復:', scrollPosition);

                // 清除保存的滾動位置，避免影響其他頁面導航
                localStorage.removeItem('tripShowScrollPosition');
            }
        }

        // 更新指定乘客的位置信息
        window.updatePassengerLocation = function(passengerIndex, location) {
            console.log(`🎯 updatePassengerLocation 被調用:`, {
                passengerIndex,
                location
            });

            const hiddenInput = document.getElementById(`passenger-${passengerIndex}-location`);
            const displayElement = document.getElementById(`passenger-${passengerIndex}-display`);
            const locationBtn = document.querySelector(
                `.passenger-location-btn[data-passenger="${passengerIndex}"]`);

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

                    // 添加成功動畫效果
                    locationBtn.classList.add('location-selected-animation');
                    setTimeout(() => {
                        locationBtn.classList.remove('location-selected-animation');
                    }, 600);
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
                    const passengerIndex = parseInt(currentSelectingPassenger);
                    updatePassengerLocation(passengerIndex, location);
                    localStorage.removeItem('currentSelectingPassenger');
                }
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
                $('.overlay').hide();
                return;
            }

            setInterval(function() {
                if (--timer < 0) {
                    timer = 0;
                    // Time's up! Show waiting for driver interface only if user has joined
                    $('#cd').hide();
                    @if ($hasJoined || (isset($hasPaidButNotConfirmed) && $hasPaidButNotConfirmed))
                        $('#waiting-driver').show();
                    @endif
                    $('.overlay').hide();
                    return;
                }

                // If countdown is within 24 hours, show countdown
                if (timer <= 86400) {
                    @if ($hasJoined || (isset($hasPaidButNotConfirmed) && $hasPaidButNotConfirmed))
                        $('#cd').show();
                    @else
                        $('#cd').hide();
                    @endif
                    $('#waiting-driver').hide();
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
            const price = 'HK$ {{ number_format($userFee, 0) }}';
            const currentPeople = '{{ $currentPeople }}';
            const maxPeople = '{{ $trip->max_people }}';

            var showInviteCode = {!! $showInvitationCode ? 'true' : 'false' !!};

            var inviteMessage = '';

            if (showInviteCode) {
                inviteMessage = '🔁 邀請碼: ' + "{{ $trip->invitation_code }}" + '\n\n點擊連結使用邀請碼登入查看詳情:';
            } else {
                inviteMessage = '\n點擊連結查看詳情並加入:';
            }

            // Get current URL, replace localhost with online domain if needed
            let shareUrl = '{{ $hasJoined ? config('app.url') : route('trips.show', ['id' => $trip->id]) }}';
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
` +
                `${inviteMessage}` +
                `
#拼車 #香港 #出行`;

            return {
                title: `${tripTitle} 拼車邀請`,
                text: shareText,
                url: shareUrl
            };
        }

        // Copy link functionality (降級方案)
        $('#copy-link-btn').on('click', function() {
            const shareData = getShareData();
            const currentUrl = shareData.url;
            const copyText = $('#copy-text');
            const originalText = copyText.text();

            const tripTitle = '{{ $trip->dropoff_location }}';
            const departureTime = '{{ $departureTime->format('Y-m-d H:i') }}';
            const price = 'HK$ {{ number_format($userFee, 0) }}';
            const currentPeople = '{{ $currentPeople }}';
            const maxPeople = '{{ $trip->max_people }}';

            var showInviteCode = {!! $showInvitationCode ? 'true' : 'false' !!};

            var inviteMessage = '';

            if (showInviteCode) {
                inviteMessage = '🔁 邀請碼: ' + "{{ $trip->invitation_code }}" + '\n\n點擊連結使用邀請碼登入查看詳情:';
            } else {
                inviteMessage = '\n點擊連結查看詳情並加入:';
            }
            const shareText = `🚗 ${tripTitle} 拼車邀請！

📍 目的地: ${tripTitle}
🕐 出發時間: ${departureTime}
💰 價格: ${price}/人
👥 目前人數: ${currentPeople}/${maxPeople}
` +
                `${inviteMessage}` +
                `
#拼車 #香港 #出行`;

            // Use modern Clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(shareText).then(function() {
                    // Successfully copied
                    copyText.text('{{ __('Copied!') }}');

                    // 顯示成功反饋
                    const button = $('#copy-link-btn');
                    button.removeClass(
                            'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600'
                        )
                        .addClass(
                            'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-200'
                        );

                    // Restore after 2 seconds
                    setTimeout(function() {
                        copyText.text(originalText);
                        button.removeClass(
                                'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-200'
                            )
                            .addClass(
                                'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600'
                            );
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
                    button.removeClass(
                            'bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30')
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
                            .addClass(
                                'bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30'
                            );
                        button.find('div').removeClass('bg-green-500').addClass('bg-blue-500');
                    }, 2000);
                } else {
                    // Copy failed
                    copyText.text('{{ __('Copy Failed') }}');
                    button.removeClass(
                            'bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30')
                        .addClass('bg-red-50 dark:bg-red-900/20');
                    button.find('div').removeClass('bg-blue-500').addClass('bg-red-500');

                    setTimeout(function() {
                        copyText.text(originalText);
                        button.removeClass('bg-red-50 dark:bg-red-900/20')
                            .addClass(
                                'bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30'
                            );
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
                        .addClass(
                            'bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30'
                        );
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
            // initializeShare();
        });

        // === 多人預訂功能 ===
        let passengerCount = 1;
        const tripType = '{{ $trip->type }}';
        const basePricePerPerson = {{ $trip->price_per_person }};
        const fourPersonDiscount = {{ $trip->four_person_discount }};
        const availableSlots = {{ $availableSlots }};

        // 優惠券相關變數
        let appliedCoupon = null;
        let couponDiscountAmount = 0;

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
            const subtotalAmount = peopleCount * pricePerPerson;
            const finalAmount = Math.max(0, subtotalAmount - couponDiscountAmount);

            $('#people-display').text(peopleCount);
            $('#price-per-person-display').text(`HK$ ${pricePerPerson.toLocaleString()}`);
            $('#subtotal-amount').text(`HK$ ${subtotalAmount.toLocaleString()}`);

            // 更新優惠券折扣顯示
            if (appliedCoupon && couponDiscountAmount > 0) {
                $('#coupon-discount-row').removeClass('hidden');
                $('#coupon-discount-amount').text(`-HK$ ${couponDiscountAmount.toLocaleString()}`);
            } else {
                $('#coupon-discount-row').addClass('hidden');
            }

            $('#total-amount').text(`HK$ ${finalAmount.toLocaleString()}`);
            $('#total-amount-btn').text(`HK$ ${finalAmount.toLocaleString()}`);

            // 為價格顯示添加動畫效果
            $('#total-amount, #price-per-person-display, #subtotal-amount').addClass('price-updated');
            setTimeout(() => {
                $('#total-amount, #price-per-person-display, #subtotal-amount').removeClass(
                    'price-updated');
            }, 300);
        }

        // 套用優惠券
        function applyCoupon() {
            const couponCode = $('#coupon-code').val().trim().toUpperCase();
            if (!couponCode) {
                showCouponError('{{ __('Please enter a coupon code') }}');
                return;
            }

            // 顯示加載狀態
            const applyBtn = $('#apply-coupon');
            const originalText = applyBtn.text();
            applyBtn.text('{{ __('Validating...') }}').prop('disabled', true);

            // 發送 AJAX 請求驗證優惠券
            $.ajax({
                url: '{{ route('coupon.validate') }}',
                method: 'POST',
                data: {
                    code: couponCode,
                    trip_id: '{{ $trip->id }}',
                    amount: calculateSubtotal(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.valid) {
                        appliedCoupon = response.coupon;
                        couponDiscountAmount = response.discount_amount;
                        showCouponSuccess(
                            `{{ __("Coupon applied! Discount: HK$") }}${response.discount_amount}`
                        );
                        updatePriceDisplay();

                        // 禁用輸入框和按鈕
                        $('#coupon-code').prop('disabled', true);
                        applyBtn.text('{{ __('Applied') }}').addClass(
                            'bg-green-600 hover:bg-green-700');

                        // 添加移除按鈕
                        if (!$('#remove-coupon').length) {
                            applyBtn.after(`
                                <button type="button" id="remove-coupon" 
                                    class="px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm">
                                    {{ __('Remove') }}
                                </button>
                            `);
                        }
                    } else {
                        showCouponError(response.message || '{{ __('Invalid coupon code') }}');
                    }
                },
                error: function() {
                    showCouponError('{{ __('Error validating coupon. Please try again.') }}');
                },
                complete: function() {
                    applyBtn.text(originalText).prop('disabled', false);
                }
            });
        }

        // 移除優惠券
        function removeCoupon() {
            appliedCoupon = null;
            couponDiscountAmount = 0;

            $('#coupon-code').prop('disabled', false).val('');
            $('#apply-coupon').text('{{ __('Apply') }}').removeClass('bg-green-600 hover:bg-green-700')
                .addClass('bg-amber-600 hover:bg-amber-700');
            $('#remove-coupon').remove();
            $('#coupon-status').addClass('hidden');
            $('#coupon-success, #coupon-error').addClass('hidden');

            updatePriceDisplay();
        }

        // 計算小計
        function calculateSubtotal() {
            const peopleCount = parseInt($('#people-count').val()) || 1;
            const pricePerPerson = calculatePricePerPerson(peopleCount);
            return peopleCount * pricePerPerson;
        }

        // 顯示優惠券成功訊息
        function showCouponSuccess(message) {
            $('#coupon-status').removeClass('hidden');
            $('#coupon-error').addClass('hidden');
            $('#coupon-success').removeClass('hidden');
            $('#coupon-success-text').text(message);
        }

        // 顯示優惠券錯誤訊息
        function showCouponError(message) {
            $('#coupon-status').removeClass('hidden');
            $('#coupon-success').addClass('hidden');
            $('#coupon-error').removeClass('hidden');
            $('#coupon-error-text').text(message);
        }

        // 創建額外乘客表單
        function createPassengerForm(index) {
            return `
                <div class="passenger-form border border-gray-200 dark:border-gray-600 rounded-lg p-4" data-passenger="${index}">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-700 dark:text-gray-200">
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
                            <x-text-input name="passengers[${index}][name]" class="block w-full border-gray-300 dark:border-gray-700" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Phone Number') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <select name="passengers[${index}][phone_country_code]"
                                    class="rounded-l-md border-gray-300 dark:border-gray-700 bg-secondary dark:bg-secondary-dark dark:text-gray-300 focus:border-primary dark:focus:border-primary-dark focus:ring-primary dark:focus:ring-primary-dark shadow-sm">
                                    <option value="+852">+852 (HK)</option>
                                    <option value="+86">+86 (CN)</option>
                                </select>
                                <x-text-input type="tel" name="passengers[${index}][phone]" required
                                    class="block w-full rounded-l-none border-l-0 border-gray-300 dark:border-gray-700"
                                    placeholder="12345678"/>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Pickup Location') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="hidden" name="passengers[${index}][pickup_location]"
                                    id="passenger-${index}-location" required>
                                <button type="button"
                                    class="passenger-location-btn w-full text-left px-3 py-2 text-sm bg-secondary dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-md dark:bg-secondary-dark dark:text-gray-300 focus:border-primary dark:focus:border-primary-dark focus:ring-primary dark:focus:ring-primary-dark shadow-sm transition-colors"
                                    data-passenger="${index}" onclick="openMapForPassenger(${index})">
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="passenger-location-display text-gray-400 dark:text-gray-500 italic"
                                            id="passenger-${index}-display">
                                            {{ __('Click to select pickup location on map') }}
                                        </span>
                                        <i
                                            class="material-icons text-gray-400 dark:text-gray-500">location_on</i>
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
                showAlertModal({
                    title: '{{ __('Booking Limit Exceeded') }}',
                    message: `{{ __('Cannot book for') }} ${selectedCount} {{ __('people. Only') }} ${availableSlots} {{ __('slots available.') }}`,
                    type: 'warning',
                    buttonText: '{{ __('OK') }}'
                });
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
                        $(this).attr('name', name.replace(/passengers\[\d+\]/,
                            `passengers[${index}]`));
                    }

                    const id = $(this).attr('id');
                    if (id && id.includes('passenger-')) {
                        $(this).attr('id', id.replace(/passenger-\d+/,
                            `passenger-${index}`));
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
                showAlertModal({
                    title: '{{ __('Terms Required') }}',
                    message: '{{ __('Please agree to the terms and conditions.') }}',
                    type: 'warning',
                    buttonText: '{{ __('OK') }}'
                });
                return;
            }

            // 驗證所有必填欄位
            let isValid = true;

            // 驗證基本欄位
            form.find('input[required], select[required]').each(function() {
                if (!$(this).val().trim()) {
                    $(this).focus();
                    showAlertModal({
                        title: '{{ __('Required Fields Missing') }}',
                        message: '{{ __('Please fill in all required fields.') }}',
                        type: 'warning',
                        buttonText: '{{ __('OK') }}'
                    });
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
                        showAlertModal({
                            title: '{{ __('Pickup Location Required') }}',
                            message: `{{ __('Please select pickup location for passenger') }} ${i + 1}`,
                            type: 'warning',
                            buttonText: '{{ __('OK') }}'
                        });
                        // 滾動到相應的乘客表單
                        const passengerForm = document.querySelector(`[data-passenger="${i}"]`);
                        if (passengerForm) {
                            passengerForm.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                        isValid = false;
                        break;
                    }
                }
            }

            if (!isValid) return;

            // 計算總金額並添加到表單（考慮優惠券折扣）
            const peopleCount = parseInt($('#people-count').val()) || 1;
            const pricePerPerson = calculatePricePerPerson(peopleCount);
            const subtotalAmount = peopleCount * pricePerPerson;
            const finalAmount = Math.max(0, subtotalAmount - couponDiscountAmount);

            // 添加金額相關資訊到表單
            form.append(`<input type="hidden" name="subtotal_amount" value="${subtotalAmount}">`);
            form.append(`<input type="hidden" name="total_amount" value="${finalAmount}">`);
            form.append(`<input type="hidden" name="price_per_person" value="${pricePerPerson}">`);

            // 添加優惠券資訊 (如果有套用)
            if (appliedCoupon) {
                form.append(`<input type="hidden" name="coupon_code" value="${appliedCoupon.code}">`);
                form.append(
                    `<input type="hidden" name="coupon_discount" value="${couponDiscountAmount}">`);
            }

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
                            $(this).find('select[name*="[phone_country_code]"]').val(
                                passengerData.phone_country_code);
                        }

                        // 恢復電話號碼
                        if (passengerData.phone) {
                            $(this).find('input[name*="[phone]"]').val(passengerData.phone);
                        }

                        // 恢復地址
                        if (passengerData.pickup_location) {
                            const locationInput = $(this).find(
                                'input[name*="[pickup_location]"]');
                            const locationDisplay = $(this).find('.passenger-location-display');
                            const locationBtn = $(this).find('.passenger-location-btn');

                            if (locationInput.length && locationDisplay.length) {
                                locationInput.val(passengerData.pickup_location);
                                locationDisplay.text(passengerData.pickup_location);
                                locationDisplay.removeClass(
                                    'text-gray-400 dark:text-gray-500 italic');
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

            // 保存當前滾動位置
            saveScrollPosition();

            // 設置當前選擇的乘客索引
            localStorage.setItem('currentSelectingPassenger', passengerIndex);

            // 打開地圖頁面
            const mapUrl = '{{ route('map') }}?passenger=' + passengerIndex + '&return=' +
                encodeURIComponent(window.location.pathname);
            window.location.href = mapUrl;
        };

        // 監聽來自地圖頁面的位置選擇事件
        window.addEventListener('passenger-location-selected', function(event) {
            const {
                passengerIndex,
                location
            } = event.detail;
            updatePassengerLocation(passengerIndex, location);
        });

        // 檢查URL參數，看是否從地圖頁面返回並帶有位置信息
        function checkMapReturnWithLocation() {
            const urlParams = new URLSearchParams(window.location.search);
            const returnedLocation = urlParams.get('location');
            const passengerIndex = urlParams.get('passenger');

            console.log('📍 檢查地圖返回參數:', {
                returnedLocation: !!returnedLocation,
                passengerIndex
            });

            if (returnedLocation && passengerIndex !== null) {
                try {
                    const location = JSON.parse(decodeURIComponent(returnedLocation));
                    console.log('📍 解析位置數據成功:', location);

                    // 標記這是從地圖返回的
                    window.isReturningFromMap = true;

                    // 先恢復表單狀態，然後更新地址
                    setTimeout(() => {
                        console.log('📍 為乘客設置地址:', passengerIndex, location);
                        updatePassengerLocation(parseInt(passengerIndex), location);

                        // 地址更新後，滾動到對應的乘客表單位置
                        setTimeout(() => {
                            const passengerForm = document.querySelector(
                                `[data-passenger="${passengerIndex}"]`);
                            if (passengerForm) {
                                const formRect = passengerForm.getBoundingClientRect();
                                const windowHeight = window.innerHeight;

                                // 計算滾動位置，讓表單顯示在螢幕中央
                                const scrollToPosition = window.scrollY + formRect.top - (
                                    windowHeight / 2) + (formRect.height / 2);

                                window.scrollTo({
                                    top: Math.max(0, scrollToPosition),
                                    behavior: 'smooth'
                                });

                                console.log('📍 滾動到乘客表單位置:', passengerIndex);
                            }
                        }, 100);
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

            // 監聽滾動事件，定期更新滾動位置（節流處理）
            let scrollTimer = null;
            $(window).on('scroll', function() {
                if (scrollTimer) {
                    clearTimeout(scrollTimer);
                }
                scrollTimer = setTimeout(() => {
                    // 只有在預訂表單可見時才保存滾動位置
                    const bookingForm = document.getElementById('group-booking-form');
                    if (bookingForm) {
                        const rect = bookingForm.getBoundingClientRect();
                        const isFormVisible = rect.top < window.innerHeight && rect.bottom > 0;

                        if (isFormVisible) {
                            saveScrollPosition();
                        }
                    }
                }, 200);
            });
        }

        // 頁面載入時的初始化
        function initializeGroupBooking() {
            // 首先恢復表單狀態
            restoreFormState();

            // 然後檢查是否從地圖返回
            checkMapReturnWithLocation();

            // 恢復滾動位置（只有在不是從地圖返回時才執行）
            setTimeout(() => {
                if (!window.isReturningFromMap) {
                    restoreScrollPosition();
                }
            }, 300);

            // 設置自動保存
            setupAutoSave();
        }

        // === 優惠券事件監聽器 ===

        // 套用優惠券按鈕
        $('#apply-coupon').on('click', function() {
            applyCoupon();
        });

        // 優惠券代碼輸入框 Enter 鍵
        $('#coupon-code').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                applyCoupon();
            }
        });

        // 移除優惠券按鈕 (動態添加的元素使用事件委託)
        $(document).on('click', '#remove-coupon', function() {
            removeCoupon();
        });

        // 優惠券代碼輸入時自動轉為大寫
        $('#coupon-code').on('input', function() {
            $(this).val($(this).val().toUpperCase());
            // 清除之前的狀態訊息
            if ($(this).val() === '') {
                $('#coupon-status').addClass('hidden');
            }
        });

        // 頁面載入時初始化
        initializeGroupBooking();

        // 調試函數：查看當前保存的滾動位置
        window.debugScrollPosition = function() {
            const saved = localStorage.getItem('tripShowScrollPosition');
            const current = window.scrollY;
            console.log('🔍 滾動位置調試信息:', {
                current: current,
                saved: saved ? parseInt(saved) : null,
                isReturningFromMap: window.isReturningFromMap || false
            });
        };

        // 邀請代碼功能
        function initializeInvitationCodeFeatures() {
            // 複製邀請代碼
            $('#copy-invitation-code').on('click', function() {
                const invitationCode = $(this).data('code');
                const button = $(this);
                const copyText = button.find('.copy-text');
                const originalText = copyText.text();

                // 嘗試使用現代 Clipboard API
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(invitationCode)
                        .then(() => {
                            // 成功複製
                            copyText.text('{{ __('Copied!') }}');
                            button.removeClass('text-blue-600 dark:text-blue-400')
                                .addClass('text-green-600 dark:text-green-400');

                            setTimeout(() => {
                                copyText.text(originalText);
                                button.removeClass('text-green-600 dark:text-green-400')
                                    .addClass('text-blue-600 dark:text-blue-400');
                            }, 2000);
                        })
                        .catch(() => {
                            // 失敗時使用備用方法
                            fallbackCopyCode(invitationCode, button, copyText, originalText);
                        });
                } else {
                    // 使用備用複製方法
                    fallbackCopyCode(invitationCode, button, copyText, originalText);
                }
            });

            // WhatsApp 分享邀請代碼
            $('#share-invitation-whatsapp').on('click', function() {
                const invitationCode = '{{ $trip->invitation_code }}';
                const tripTitle = '{{ $trip->title }}';
                const loginUrl = '{{ route('login') }}';

                const message = `{{ __('Join my carpool trip!') }}\n\n` +
                    `{{ __('Trip') }}: ${tripTitle}\n` +
                    `{{ __('Invitation Code') }}: ${invitationCode}\n\n` +
                    `{{ __('How to join') }}:\n` +
                    `1. {{ __('Visit') }}: ${loginUrl}\n` +
                    `2. {{ __('Click "Join Trip" tab') }}\n` +
                    `3. {{ __('Enter invitation code and your phone number') }}\n\n` +
                    `{{ __('No registration required!') }}`;

                const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
            });

            // 複製登錄連結
            $('#copy-invitation-link').on('click', function() {
                const loginUrl = '{{ route('login') }}';
                const button = $(this);
                const originalText = button.find('span:last-child').text();

                // 嘗試使用現代 Clipboard API
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(loginUrl)
                        .then(() => {
                            // 成功複製
                            button.find('span:last-child').text('{{ __('Copied!') }}');
                            button.removeClass(
                                    'bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600'
                                )
                                .addClass(
                                    'bg-green-600 dark:bg-green-700 hover:bg-green-700 dark:hover:bg-green-600'
                                );

                            setTimeout(() => {
                                button.find('span:last-child').text(originalText);
                                button.removeClass(
                                        'bg-green-600 dark:bg-green-700 hover:bg-green-700 dark:hover:bg-green-600'
                                    )
                                    .addClass(
                                        'bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600'
                                    );
                            }, 2000);
                        })
                        .catch(() => {
                            // 失敗時使用備用方法
                            fallbackCopyLink(loginUrl, button, originalText);
                        });
                } else {
                    // 使用備用複製方法
                    fallbackCopyLink(loginUrl, button, originalText);
                }
            });
        }

        // 備用複製邀請代碼方法
        function fallbackCopyCode(code, button, copyText, originalText) {
            const textArea = document.createElement('textarea');
            textArea.value = code;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    copyText.text('{{ __('Copied!') }}');
                    button.removeClass('text-blue-600 dark:text-blue-400')
                        .addClass('text-green-600 dark:text-green-400');

                    setTimeout(() => {
                        copyText.text(originalText);
                        button.removeClass('text-green-600 dark:text-green-400')
                            .addClass('text-blue-600 dark:text-blue-400');
                    }, 2000);
                } else {
                    copyText.text('{{ __('Copy Failed') }}');
                    button.removeClass('text-blue-600 dark:text-blue-400')
                        .addClass('text-red-600 dark:text-red-400');

                    setTimeout(() => {
                        copyText.text(originalText);
                        button.removeClass('text-red-600 dark:text-red-400')
                            .addClass('text-blue-600 dark:text-blue-400');
                    }, 2000);
                }
            } catch (err) {
                copyText.text('{{ __('Copy Failed') }}');
                button.removeClass('text-blue-600 dark:text-blue-400')
                    .addClass('text-red-600 dark:text-red-400');

                setTimeout(() => {
                    copyText.text(originalText);
                    button.removeClass('text-red-600 dark:text-red-400')
                        .addClass('text-blue-600 dark:text-blue-400');
                }, 2000);
            }

            document.body.removeChild(textArea);
        }

        // 備用複製連結方法
        function fallbackCopyLink(url, button, originalText) {
            const textArea = document.createElement('textarea');
            textArea.value = url;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    button.find('span:last-child').text('{{ __('Copied!') }}');
                    button.removeClass('bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600')
                        .addClass('bg-green-600 dark:bg-green-700 hover:bg-green-700 dark:hover:bg-green-600');

                    setTimeout(() => {
                        button.find('span:last-child').text(originalText);
                        button.removeClass(
                                'bg-green-600 dark:bg-green-700 hover:bg-green-700 dark:hover:bg-green-600'
                            )
                            .addClass(
                                'bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600'
                            );
                    }, 2000);
                } else {
                    button.find('span:last-child').text('{{ __('Copy Failed') }}');
                    button.removeClass('bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600')
                        .addClass('bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600');

                    setTimeout(() => {
                        button.find('span:last-child').text(originalText);
                        button.removeClass(
                                'bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600')
                            .addClass(
                                'bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600'
                            );
                    }, 2000);
                }
            } catch (err) {
                button.find('span:last-child').text('{{ __('Copy Failed') }}');
                button.removeClass('bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600')
                    .addClass('bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600');

                setTimeout(() => {
                    button.find('span:last-child').text(originalText);
                    button.removeClass(
                            'bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600')
                        .addClass(
                            'bg-gray-600 dark:bg-gray-700 hover:bg-gray-700 dark:hover:bg-gray-600');
                }, 2000);
            }

            document.body.removeChild(textArea);
        }

        // 初始化邀請代碼功能 - 只在群組預訂時執行
        @if (($hasJoined || (isset($hasPaidButNotConfirmed) && $hasPaidButNotConfirmed)) && $isGroupBooking)
            initializeInvitationCodeFeatures();
        @endif
    });
</script>

<!-- Alert Modal -->
<x-alert-modal />
