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

        <!-- 行程資訊卡片 -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center text-sm">
                <span id="pickup_location" class="text-gray-900 dark:text-gray-100">{{ session('location') }}</span>
                <span class="text-xl text-gray-900 dark:text-gray-100 px-2 mb-1">&#10230;</span>
                <span class="text-gray-900 dark:text-gray-100">{{ $trip->dropoff_location }}</span>
            </div>

            <div class="flex justify-between items-start mt-2">
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
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600 dark:text-gray-300">{{ __('Joined User') }}</span>
                    <span
                        class="font-semibold text-gray-900 dark:text-gray-100">{{ $currentPeople }}/{{ $trip->max_people }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-300">{{ __('Status') }}</span>
                    <span
                        class="px-2 py-1 rounded text-sm
                        @if ($trip->trip_status === 'awaiting') bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200
                        @elseif($trip->trip_status === 'voting') bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200
                        @elseif($trip->trip_status === 'departed') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200
                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif">
                        {{ ucfirst($trip->trip_status) }}
                    </span>
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
                                @if ($join->pickup_location)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $join->pickup_location }}
                                    </div>
                                @endif
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
                    <button type="submit"
                        class="w-full mt-4 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white py-4 rounded-xl font-semibold text-lg transition shadow-md"
                        x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-join-trip')">
                        {{ __('Join') }} - HK$ {{ number_format($price, 0) }}
                    </button>

                    <x-modal name="confirm-join-trip" focusable>
                        <form action="{{ route('payment.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                            <input type="hidden" name="amount" value="{{ $deposit }}">
                            <input type="hidden" name="pickup_location" value="{{ session('location') }}">
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

        @if(!$hasLeft)
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

        $('#confirm-leave').on('click', function() {
            if ($(this).is(':checked')) {
                $('#leave-button').prop("disabled", false);
            } else {
                $('#leave-button').prop("disabled", true);
            }
        });

        // 倒計時器函數
        let timer = function(date) {
            let timer = Math.round(new Date(date).getTime() / 1000) - Math.round(new Date().getTime() / 1000);
            let days, hours, minutes, seconds;
            
            // 如果超過24小時，不顯示倒計時
            if (timer > 86400) { // 86400秒 = 24小時
                $('#cd').hide();
                return;
            }
            
            setInterval(function() {
                if (--timer < 0) {
                    timer = 0;
                }
                
                // More than 1 hour left
                if (timer > 3600) {
                    $('.operations').show();
                    $('.overlay').hide();
                }
                // 1 day left
                // 如果倒計時進入24小時內，顯示倒計時
                if (timer <= 86400) {
                    $('#cd').show();

                // 1 hour left
                if (timer <= 3600) {
                    $('.overlay').hide();
                }

                    
                    hours = parseInt((timer / 60 / 60) % 24, 10);
                    minutes = parseInt((timer / 60) % 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    hours = hours < 10 ? "0" + hours : hours;
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    $('#cd-hours').html(hours);
                    $('#cd-minutes').html(minutes);
                    $('#cd-seconds').html(seconds);
                    
                    // 根據剩餘時間改變顏色
                    if (timer <= 3600) { // 1小時內
                        $('#cd').removeClass().addClass('bg-red-600 text-white rounded-xl p-4 text-center shadow-md mt-6');
                    } else {
                        $('#cd').removeClass().addClass('bg-orange-600 text-white rounded-xl p-4 text-center shadow-md mt-6');
                    }
                } else {
                    $('#cd').hide();
                }
            }, 1000);
        };



        // 使用倒計時器
        const plannedDepartureTime = new Date('{{ $trip->planned_departure_time }}');
        timer(plannedDepartureTime);

        // 複製連結功能
        $('#copy-link-btn').on('click', function() {
            const currentUrl = window.location.href;
            const button = $(this);
            const originalText = button.html();
            
            // 使用現代的 Clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(currentUrl).then(function() {
                    // 成功複製
                    button.html('<span class="material-icons text-sm">check</span>{{ __("Copied!") }}');
                    button.css('background-color', '#22c55e');
                    
                    // 2秒後恢復原狀
                    setTimeout(function() {
                        button.html(originalText);
                        button.css('background-color', '#6b7280');
                    }, 2000);
                }).catch(function(err) {
                    // 複製失敗，使用備用方法
                    fallbackCopyTextToClipboard(currentUrl, button, originalText);
                });
            } else {
                // 不支持 Clipboard API，使用備用方法
                fallbackCopyTextToClipboard(currentUrl, button, originalText);
            }
        });

        // 備用複製方法（針對較老的瀏覽器）
        function fallbackCopyTextToClipboard(text, button, originalText) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            
            // 避免在 iPhone 上出現縮放
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
                    // 成功複製
                    button.html('<span class="material-icons text-sm">check</span>{{ __("Copied!") }}');
                    button.css('background-color', '#22c55e');
                    
                    // 2秒後恢復原狀
                    setTimeout(function() {
                        button.html(originalText);
                        button.css('background-color', '#6b7280');
                    }, 2000);
                } else {
                    // 複製失敗
                    button.html('<span class="material-icons text-sm">error</span>{{ __("Copy Failed") }}');
                    button.css('background-color', '#ef4444');
                    
                    setTimeout(function() {
                        button.html(originalText);
                        button.css('background-color', '#6b7280');
                    }, 2000);
                }
            } catch (err) {
                // 複製失敗
                button.html('<span class="material-icons text-sm">error</span>{{ __("Copy Failed") }}');
                button.css('background-color', '#ef4444');
                
                setTimeout(function() {
                    button.html(originalText);
                    button.css('background-color', '#6b7280');
                }, 2000);
            }
            
            document.body.removeChild(textArea);
        }

        // WhatsApp 分享功能
        $('#whatsapp-share-btn').on('click', function() {
            // 生成分享訊息
            const tripTitle = '{{ $trip->dropoff_location }}';
            const departureTime = '{{ $departureTime->format("Y-m-d H:i") }}';
            const price = 'HK$ {{ number_format($price, 0) }}';
            const currentPeople = '{{ $currentPeople }}';
            const maxPeople = '{{ $trip->max_people }}';
            
            // 獲取當前 URL，如果是 localhost 則替換為線上域名
            let shareUrl = window.location.href;
            if (shareUrl.includes('localhost') || shareUrl.includes('127.0.0.1')) {
                // 替換為線上域名（從 Laravel config 讀取）
                const appUrl = '{{ config("app.url") }}';
                if (appUrl && !appUrl.includes('localhost')) {
                    shareUrl = shareUrl.replace(/https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/, appUrl);
                } else {
                    // 備用域名，請在這裡替換為你的實際域名
                    // 例如: 'https://carpool.yourdomain.com' 或 'https://yourdomain.com'
                    shareUrl = shareUrl.replace(/https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/, 'https://your-actual-domain.com');
                }
            }
            
            // 構建分享訊息
            const message = `🚗 拼車邀請！\n\n` +
                           `📍 目的地: ${tripTitle}\n` +
                           `🕐 出發時間: ${departureTime}\n` +
                           `💰 費用: ${price} /人\n` +
                           `👥 目前人數: ${currentPeople}/${maxPeople}\n\n` +
                           `點擊連結查看詳情並加入:\n${shareUrl}\n\n` +
                           `#拼車 #香港 #出行`;
            
            // 編碼訊息
            const encodedMessage = encodeURIComponent(message);
            
            // 生成 WhatsApp 分享連結
            const whatsappUrl = `https://wa.me/?text=${encodedMessage}`;
            
            // 在新視窗打開 WhatsApp
            window.open(whatsappUrl, '_blank');
        });
    });
</script>
