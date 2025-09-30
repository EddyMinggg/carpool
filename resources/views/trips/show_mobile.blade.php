@section('Title', $trip->dropoff_location)
@php
    $deposit = ($trip->base_price / $trip->max_people) * 0.2;
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <button onclick="history.back()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <span class="material-icons text-gray-700 dark:text-gray-300">arrow_back</span>
            </button>
            <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                {{ __('Trip Details') }}
            </h2>
            <div class="w-10"></div>
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

        <!-- 位置選擇器 -->
        <div class="mb-4">
            <div id="location-picker"
                class="flex items-center text-sm mt-4 bg-white dark:bg-gray-800 rounded-xl p-2 shadow-md border border-gray-100 dark:border-gray-700 cursor-pointer transition-all hover:shadow-lg hover:scale-[1.02] active:scale-98"
                onclick="window.location.href='{{ route('map') }}'">
                <i class="text-gray-400 dark:text-gray-500 material-icons" id="location_pin">&#xe1b7;</i>
                <span
                    class="ms-2 {{ session('location') == null ? 'italic text-gray-400 dark:text-gray-500' : 'text-gray-900 dark:text-gray-100' }}"
                    id="pickup_location">{{ session('location') ?? __('Pick your location...') }}</span>
            </div>
        </div>

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
            <!-- 路線顯示 - 垂直佈局，支持長地址名稱 -->
            <div class="mb-4">
                <div class="flex items-start text-sm space-x-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center mb-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2 flex-shrink-0"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pickup</div>
                        </div>
                        @php
                            // 優先使用新的 session location，如果沒有則回退到 trip_join 中的記錄
                            $sessionLocation = session('location');
                            $userJoin = $trip->joins->where('user_id', auth()->id())->first();
                            $fallbackLocation = $userJoin ? $userJoin->pickup_location : null;
                            $displayLocation = $sessionLocation ?: $fallbackLocation;
                        @endphp
                        <div id="pickup_location_display"
                            class="text-gray-900 dark:text-gray-100 font-medium leading-tight break-words">
                            {{ $displayLocation ?: __('Select pickup location') }}
                        </div>
                    </div>
                </div>

                <!-- 箭頭 -->
                <div class="flex justify-center my-4">
                    <span class="text-gray-400 dark:text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </span>
                </div>

                <div class="flex items-start text-sm space-x-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center mb-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2 flex-shrink-0"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Destination
                            </div>
                        </div>
                        <div class="text-gray-900 dark:text-gray-100 font-medium leading-tight break-words">
                            {{ $trip->dropoff_location }}
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
                                <span
                                    class="text-blue-600 dark:text-blue-300 font-semibold text-sm">{{ substr($join->user->username, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $join->user->username }}
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
                            $userJoin = $trip->joins->where('user_id', auth()->id())->first();
                            $confirmedLocation = $userJoin ? $userJoin->pickup_location : null;
                        @endphp
                        <div class="font-medium text-gray-900 dark:text-gray-100 break-words">
                            {{ $confirmedLocation ?: __('Location not set') }}
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


        @if ($trip->trip_status == 'charging')
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
                            {{ __('Time to make your remaining payment! You will be denied of the trip if you didn\'t finish the payment before departure!') }}
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
                                <span id="default-tooltip-message">Copy to clipboard</span>
                                <span id="success-tooltip-message" class="hidden">Copied!</span>
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


        <!-- 操作按鈕 -->
        <div class="operations space-y-6 hidden">
            @if ($hasLeft)
                <div class="mt-8 flex justify-center text-center px-4">
                    <h2 class="text-md text-gray-900 dark:text-gray-300 font-black">
                        {{ __('You have left / was kicked from the trip.') }}
                    </h2>
                </div>
            @else
                @if (!$hasJoined)
                    <!-- 加入拼車表單 -->
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
                                    x-on:click="$dispatch('close'); setTimeout(() => { document.getElementById('location-picker').scrollIntoView({ behavior: 'smooth' }); setTimeout(() => document.getElementById('location-picker').click(), 500); }, 100);">
                                    Select Location
                                </x-primary-button>
                            </div>
                        </div>
                    </x-modal>

                    <x-modal name="confirm-join-trip" focusable>
                        <form action="{{ route('payment.create') }}" method="POST" id="join-trip-form">
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                            <input type="hidden" name="amount" value="{{ $deposit }}">
                            <input type="hidden" name="pickup_location" id="join-pickup-location"
                                value="{{ session('location') }}">
                            <div class="p-8 items-start">
                                <h2 class="text-lg text-gray-900 dark:text-gray-300 font-black">
                                    {{ __('Are you sure you want to join the trip?') }}
                                </h2>

                                <div
                                    class="mt-8 flow-root sm:mx-0 overflow-x-auto text-md text-gray-900 dark:text-gray-300">
                                    <span class="font-normal">
                                        {{ __('deposit_warning') }}
                                    </span>
                                    <span class="text-red-500 dark:text-red-400 font-black">
                                        {{ __('which WILL NOT be refunded if you decided leave the carpool.') }}
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
                                        {{ '$' . $deposit }}
                                    </span>
                                </div>
                                <div class="flex mt-6">
                                    <div class="flex items-center h-5">
                                        <input id="confirm-join" type="checkbox" value=""
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
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
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <x-primary-button id="proceed-button"
                                        class="ms-3 disabled:bg-gray-400 dark:disabled:bg-gray-500 disabled:text-gray-300 dark:disabled:text-gray-700"
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
                                            {{ __('The deposit WILL NOT be refunded if you decided leave the carpool.') }}
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
                                            {{ __('Deposit Amount: ') }}
                                        </span>
                                        <span class="font-black underline">
                                            {{ '$' . $deposit }}
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
            <!-- 分享按鈕 -->
            <div class="hidden operations">
                <div class="flex gap-3 mt-4">
                    <button id="whatsapp-share-btn"
                        class="flex-1 py-3 rounded-xl font-semibold flex items-center justify-center gap-3 transition shadow-md text-white"
                        style="background-color: #25D366;" onmouseover="this.style.backgroundColor='#1DA851'"
                        onmouseout="this.style.backgroundColor='#25D366'">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.787" />
                        </svg>
                        {{ __('Whatsapp Share') }}
                    </button>
                    <button id="copy-link-btn"
                        class="flex-1 py-3 rounded-xl font-semibold flex items-center justify-center gap-3 transition shadow-md text-white"
                        style="background-color: #6b7280;" onmouseover="this.style.backgroundColor='#4b5563'"
                        onmouseout="this.style.backgroundColor='#6b7280'">
                        <span class="material-icons text-sm">link</span>
                        {{ __('Copy Link') }}
                    </button>
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
</style>

<script type="module">
    $(document).ready(function() {

        // Listen to location selection events
        window.addEventListener('location-selected', function(event) {
            const location = event.detail.location;
            if (location && location.formatted_address) {
                // Update displayed location
                const displayElement = document.querySelector('#pickup_location_display span');
                if (displayElement) {
                    displayElement.textContent = location.formatted_address;
                    displayElement.classList.remove('text-gray-400', 'dark:text-gray-500', 'italic');
                    displayElement.classList.add('text-gray-900', 'dark:text-gray-100');
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

        // Copy link functionality
        $('#copy-link-btn').on('click', function() {
            const currentUrl = window.location.href;
            const button = $(this);
            const originalText = button.html();

            // Use modern Clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(currentUrl).then(function() {
                    // Successfully copied
                    button.html(
                        '<span class="material-icons text-sm">check</span>{{ __('Copied!') }}'
                    );
                    button.css('background-color', '#22c55e');

                    // Restore after 2 seconds
                    setTimeout(function() {
                        button.html(originalText);
                        button.css('background-color', '#6b7280');
                    }, 2000);
                }).catch(function(err) {
                    // Copy failed, use fallback method
                    fallbackCopyTextToClipboard(currentUrl, button, originalText);
                });
            } else {
                // Clipboard API not supported, use fallback method
                fallbackCopyTextToClipboard(currentUrl, button, originalText);
            }
        });

        // Fallback copy method (for older browsers)
        function fallbackCopyTextToClipboard(text, button, originalText) {
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
                if (successful) {
                    // Successfully copied
                    button.html('<span class="material-icons text-sm">check</span>{{ __('Copied!') }}');
                    button.css('background-color', '#22c55e');

                    // Restore after 2 seconds
                    setTimeout(function() {
                        button.html(originalText);
                        button.css('background-color', '#6b7280');
                    }, 2000);
                } else {
                    // Copy failed
                    button.html('<span class="material-icons text-sm">error</span>{{ __('Copy Failed') }}');
                    button.css('background-color', '#ef4444');

                    setTimeout(function() {
                        button.html(originalText);
                        button.css('background-color', '#6b7280');
                    }, 2000);
                }
            } catch (err) {
                // Copy failed
                button.html('<span class="material-icons text-sm">error</span>{{ __('Copy Failed') }}');
                button.css('background-color', '#ef4444');

                setTimeout(function() {
                    button.html(originalText);
                    button.css('background-color', '#6b7280');
                }, 2000);
            }

            document.body.removeChild(textArea);
        }

        // WhatsApp share functionality
        $('#whatsapp-share-btn').on('click', function() {
            // Generate share message
            const tripTitle = '{{ $trip->dropoff_location }}';
            const departureTime = '{{ $departureTime->format('Y-m-d H:i') }}';
            const price = 'HK$ {{ number_format($price, 0) }}';
            const currentPeople = '{{ $currentPeople }}';
            const maxPeople = '{{ $trip->max_people }}';

            // Get current URL, replace localhost with online domain if needed
            let shareUrl = window.location.href;
            if (shareUrl.includes('localhost') || shareUrl.includes('127.0.0.1')) {
                // Replace with online domain (read from Laravel config)
                const appUrl = '{{ config('app.url') }}';
                if (appUrl && !appUrl.includes('localhost')) {
                    shareUrl = shareUrl.replace(/https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/, appUrl);
                } else {
                    // Backup domain, replace with your actual domain here
                    // e.g.: 'https://carpool.yourdomain.com' or 'https://yourdomain.com'
                    shareUrl = shareUrl.replace(/https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/,
                        'https://your-actual-domain.com');
                }
            }

            // Build share message
            const message = `🚗 Carpool Invitation!\n\n` +
                `📍 Destination: ${tripTitle}\n` +
                `🕐 Departure Time: ${departureTime}\n` +
                `💰 Price: ${price} per person\n` +
                `👥 Current Occupancy: ${currentPeople}/${maxPeople}\n\n` +
                `Click the link to view details and join:\n${shareUrl}\n\n` +
                `#Carpool #HongKong #Travel`;

            // Encode message
            const encodedMessage = encodeURIComponent(message);

            // Generate WhatsApp share link
            const whatsappUrl = `https://wa.me/?text=${encodedMessage}`;

            // Open WhatsApp in new window
            window.open(whatsappUrl, '_blank');
        });
    });
</script>
