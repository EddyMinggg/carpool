@section('Title', 'Dashboard')
<x-app-layout>

    @php
        // 使用控制器傳遞的數據，不要重新定義
        // $dates 和 $activeDate 已經在控制器中正確設定
    @endphp

    <div x-data="{
        activeDate: '{{ $activeDate }}',
        dates: {{ json_encode($dates->values()) }},
        groupedTrips: {{ json_encode($groupedTrips) }},
        currentIndex: {{ $dates->search($activeDate) ?: 0 }},
        showDatePicker: false,
    
    
    
        get trips() {
            return this.groupedTrips[this.activeDate] || [];
        },
    
        // 重新設計的日曆生成器 - 支援2週限制
        get calendarDays() {
            // 使用當前月份 (2025年10月)
            const year = 2025;
            const month = 9; // 0-based, 9 = October
    
            // 計算今天和2週後的日期
            const today = new Date(2025, 9, 5); // 2025年10月5日
            today.setHours(0, 0, 0, 0);
    
            const twoWeeksLater = new Date(today);
            twoWeeksLater.setDate(today.getDate() + 14);
    
            // 獲取本月第一天是星期幾 (0 = Sunday)
            const firstDay = new Date(year, month, 1);
            const firstDayWeekday = firstDay.getDay();
    
            // 獲取本月有多少天
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
    
            const calendarDays = [];
    
            // 添加前面月份的空白日期
            for (let i = 0; i < firstDayWeekday; i++) {
                const prevMonthDate = new Date(year, month, 1 - (firstDayWeekday - i));
                calendarDays.push({
                    dayNumber: prevMonthDate.getDate(),
                    date: this.formatDate(prevMonthDate),
                    isCurrentMonth: false,
                    hasTrips: false,
                    selectable: false,
                    inTwoWeekRange: false,
                    dateType: 'other-month'
                });
            }
    
            // 添加本月的日期
            for (let day = 1; day <= daysInMonth; day++) {
                const currentDate = new Date(year, month, day);
                currentDate.setHours(0, 0, 0, 0);
    
                const dateStr = this.formatDate(currentDate);
                const hasTrips = this.groupedTrips[dateStr] && this.groupedTrips[dateStr].length > 0;
    
                // 檢查是否在未來2週內
                const inTwoWeekRange = currentDate >= today && currentDate <= twoWeeksLater;
    
                // 只有在2週內且有班車才能選擇
                const selectable = inTwoWeekRange && hasTrips;
    
                // 確定日期類型
                let dateType;
                if (!inTwoWeekRange) {
                    dateType = 'out-of-range';
                } else if (hasTrips) {
                    dateType = 'has-trips';
                } else {
                    dateType = 'no-trips-in-range';
                }
    
                calendarDays.push({
                    dayNumber: day,
                    date: dateStr,
                    isCurrentMonth: true,
                    hasTrips: hasTrips,
                    selectable: selectable,
                    inTwoWeekRange: inTwoWeekRange,
                    dateType: dateType
                });
            }
    
            // 補齊剩餘的格子到42個 (6週 x 7天)
            const remainingDays = 42 - calendarDays.length;
            for (let i = 1; i <= remainingDays; i++) {
                const nextMonthDate = new Date(year, month + 1, i);
                nextMonthDate.setHours(0, 0, 0, 0);
    
                const dateStr = this.formatDate(nextMonthDate);
                const hasTrips = this.groupedTrips[dateStr] && this.groupedTrips[dateStr].length > 0;
    
                // 檢查是否在未來2週內
                const inTwoWeekRange = nextMonthDate >= today && nextMonthDate <= twoWeeksLater;
                const selectable = inTwoWeekRange && hasTrips;
    
                let dateType;
                if (!inTwoWeekRange) {
                    dateType = 'other-month';
                } else if (hasTrips) {
                    dateType = 'has-trips';
                } else {
                    dateType = 'no-trips-in-range';
                }
    
                calendarDays.push({
                    dayNumber: i,
                    date: dateStr,
                    isCurrentMonth: false,
                    hasTrips: hasTrips,
                    selectable: selectable,
                    inTwoWeekRange: inTwoWeekRange,
                    dateType: dateType
                });
            }
    
            return calendarDays;
        },
    
        // 輔助函數：格式化日期為 YYYY-MM-DD
        formatDate(date) {
            return date.getFullYear() + '-' +
                String(date.getMonth() + 1).padStart(2, '0') + '-' +
                String(date.getDate()).padStart(2, '0');
        },
    
        selectDate(date, index) {
            this.activeDate = date;
            this.currentIndex = index;
        },
    
        // 日曆日期選擇方法
        selectDateFromCalendar(date) {
            if (this.groupedTrips[date] && this.groupedTrips[date].length > 0) {
                this.activeDate = date;
                const index = this.dates.indexOf(date);
                if (index !== -1) {
                    this.currentIndex = index;
                }
                this.showDatePicker = false;
                console.log('選擇日期:', date, '班車數量:', this.trips.length);
            }
        },
    
    
    
        prevDate() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.activeDate = this.dates[this.currentIndex];
            }
        },
    
        nextDate() {
            if (this.currentIndex < this.dates.length - 1) {
                this.currentIndex++;
                this.activeDate = this.dates[this.currentIndex];
            }
        }
    }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Success Messages -->
        @if (session('verified'))
            <div class="px-4 pt-4">
                <div class="flex items-center justify-between bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg"
                    x-data="{ show: true }" x-show="show" x-transition>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <div>
                            <div class="font-medium">{{ __('Email Verified Successfully!') }}</div>
                            <div class="text-sm">
                                {{ __('Your email address has been verified. You now have full access to all features.') }}
                            </div>
                        </div>
                    </div>
                    <button @click="show = false"
                        class="float-right text-green-500 hover:text-green-700 ml-2">&times;</button>
                </div>
            </div>
        @endif

        <!-- 日期選擇器 -->
        <div class="relative px-4 pt-4 pb-2">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Select Date') }}
                </h2>
                <!-- Date Picker Button -->
                <button @click="showDatePicker = !showDatePicker"
                    class="flex items-center gap-2 bg-primary dark:bg-primary-dark hover:bg-primary-accent dark:hover:bg-primary px-3 py-2 rounded-lg transition-colors">
                    <span class="material-icons text-base text-gray-200 dark:text-gray-300">calendar_today</span>
                    {{-- <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ __('Date') }}</span> --}}
                </button>
            </div>

            <!-- Date Picker Modal -->
            <div x-show="showDatePicker" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                @click.outside="showDatePicker = false" style="display: none;">

                <div class="bg-white dark:bg-secondary-dark rounded-lg shadow-xl max-w-sm w-full mx-4 p-6">
                    <div class="flex justify-end items-center mb-4">
                        {{-- <h3 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Select Date') }}</h3> --}}
                        <button @click="showDatePicker = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- 月份標題 -->
                    <div class="text-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ __('2025 October') }}</h4>
                    </div>

                    <!-- 星期標題 -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <div class="text-center text-sm font-medium text-gray-500 py-2">{{ __('Sun') }}</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">{{ __('Mon') }}</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">{{ __('Tue') }}</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">{{ __('Wed') }}</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">{{ __('Thu') }}</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">{{ __('Fri') }}</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">{{ __('Sat') }}</div>
                    </div>

                    <!-- 日曆網格 -->
                    <div class="grid grid-cols-7 gap-1">
                        <template x-for="day in calendarDays" :key="day.date">
                            <button @click="day.selectable && selectDateFromCalendar(day.date)"
                                :disabled="!day.selectable"
                                :class="{
                                    // 當前選中的日期
                                    'bg-primary border-2 border-primary-dark text-white': day.date === activeDate,
                                
                                    // 有班車的日期（綠色）
                                    'bg-green-100 border-green-400 text-green-600 border-2 hover:bg-green-200': day
                                        .dateType === 'has-trips' && day.date !== activeDate,
                                
                                    // 2週內但沒有班車（橙色邊框）
                                    'bg-orange-50 border-orange-300 text-orange-500 border-2 cursor-not-allowed': day
                                        .dateType === 'no-trips-in-range',
                                
                                    // 超出範圍或其他月份（只用灰色文字）
                                    'text-gray-400 cursor-not-allowed': day.dateType === 'out-of-range' || day
                                        .dateType === 'other-month'
                                }"
                                class="h-10 w-10 text-sm rounded-lg flex items-center justify-center transition-colors duration-200 font-medium">
                                <span x-text="day.dayNumber"></span>
                            </button>
                        </template>
                    </div>

                    <!-- 圖例 -->
                    <div class="mt-4 flex justify-center gap-4 text-xs text-gray-600">
                        <div class="flex items-center gap-1">
                            <div class="w-3 h-3 bg-green-100 border border-green-400 rounded"></div>
                            <span class="text-gray-700 dark:text-gray-500">{{ __('Has Trips') }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-3 h-3 bg-orange-50 border border-orange-300 rounded"></div>
                            <span class="text-gray-700 dark:text-gray-500">{{ __('No Trips') }}</span>
                        </div>
                    </div>
                </div>
            </div> <!-- Carousel Date Selector -->
            <div class="flex gap-3 overflow-x-auto no-scrollbar snap-x snap-mandatory pb-2"
                style="-webkit-overflow-scrolling: touch; touch-action: pan-x;">
                <template x-for="(date, index) in dates" :key="date">
                    <button
                        @click="selectDate(date, index); $el.scrollIntoView({behavior: 'smooth', inline: 'center', block: 'nearest'})"
                        :class="activeDate === date ? 'bg-primary dark:bg-primary-dark text-white shadow-lg scale-105' :
                            'bg-gray-300 dark:bg-secondary-accent text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-neutral-600 border-gray-200 dark:border-gray-600'"
                        class="min-w-[80px] px-3 py-3 rounded-xl font-semibold text-sm transition-all duration-300 snap-center focus:outline-none whitespace-nowrap shadow-sm">
                        <div class="w-full text-xs opacity-75"
                            x-text="new Date(date).toLocaleDateString('en', {weekday: 'short'})"></div>
                        <div class="w-full text-base font-bold mt-1"
                            x-text="new Date(date).toLocaleDateString('en', {day: 'numeric'})"></div>
                        <div class="w-full text-xs opacity-75"
                            x-text="new Date(date).toLocaleDateString('en', {month: 'short'})"></div>
                    </button>
                </template>
            </div>
        </div>
        <!-- Trip 列表 -->
        <div class="flex flex-col gap-3 px-4 py-4">
            <template x-for="trip in trips" :key="trip.id">
                <div :class="{
                    'border-primary-accent border-2': trip.type === 'golden',
                    'border-gray-100 dark:border-gray-700': trip.type !== 'golden' && !trip.is_expired,
                    'border-gray-300 dark:border-gray-600 opacity-40': trip.type !== 'golden' && trip.is_expired
                }"
                    :class="(trip.type !== 'golden' && trip.is_expired) ? 'cursor-not-allowed' : 'cursor-pointer'"
                    :class="!(trip.type !== 'golden' && trip.is_expired) ?
                    'hover:shadow-lg hover:scale-[1.02] active:scale-98' : ''"
                    class="bg-white dark:bg-secondary-accent rounded-xl p-6 sm:p-8 shadow-md border transition-all"
                    @click="!(trip.type !== 'golden' && trip.is_expired) ? (window.location='/trips/' + trip.id) : null">

                    <!-- Golden Hour 特殊標記 -->
                    <template x-if="trip.type === 'golden'">
                        <div class="flex items-center justify-center md:justify-start mb-6">
                            <div
                                class="bg-primary backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg w-full flex items-center justify-between gap-2 border border-white/30">
                                <span class="material-icons text-base">star</span>
                                <div class="text-center">
                                    <div>{{ __('Golden Hour') }}</div>
                                    <div class="mt-1">{{ __('Guaranteed Departure!') }}</div>
                                </div>
                                <span class="material-icons text-base">star</span>
                            </div>
                        </div>
                    </template>

                    <!-- 4人折扣提示 -->
                    <div class="flex justify-start mb-3">
                        <template x-if="trip.type === 'normal' && trip.four_person_discount > 0 && !trip.is_expired">
                            <span
                                class="bg-primary dark:bg-primary text-white px-2 py-1 rounded-full text-xs font-medium">
                                <span x-text="'4+ {{ __('people') }}: -HK$' + trip.four_person_discount"></span>
                            </span>
                        </template>
                    </div>

                    <template x-if="trip.is_expired">
                        <div class="mb-4">
                            <span
                                class="bg-gray-400 text-gray-900 dark:text-black inline-flex items-center px-2 py-1 rounded-2xl text-xs font-medium">
                                <i class="material-icons text-sm me-2">&#xe899;</i>
                                <span
                                    x-text="trip.is_expired ? '{{ __('Booking Closed') }}' : trip.type_label"></span>
                            </span>
                        </div>
                    </template>

                    <!-- 上方：時間和價格 -->
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <div class="text-gray-700 dark:text-gray-200 text-3xl font-bold"
                                x-text="trip.formatted_departure_time">
                            </div>

                        </div>
                        <div class="text-right ml-4">
                            <div class="text-primary-accent text-xl sm:text-2xl font-bold"
                                x-text="'HK$ ' + trip.price">
                            </div>
                            <div class="text-xs text-gray-700 dark:text-gray-200" x-text="'{{ __('per person') }}'">
                            </div>
                        </div>
                    </div>

                    <!-- 中間：目的地和乘客信息 -->
                    <div class="flex mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-1">
                                <i class="text-gray-600 dark:text-gray-200 material-icons">&#xe5c8;</i>
                                <div class="text-gray-600 dark:text-gray-200 text-lg font-semibold truncate"
                                    style="margin-top: 0.1rem;"
                                    x-text="trip.dropoff_location || '{{ __('Huafa') }}'">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 下方：最少人數要求和特殊提示 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <!-- 最少人數要求 -->
                            <div class="flex items-center">
                                <span class="material-icons me-1 text-sm text-gray-400 dark:text-gray-500">info</span>
                                <span class="text-xs text-gray-600 dark:text-gray-200"
                                    x-text="'{{ __('Min') }} ' + trip.min_passengers + ' {{ __('people') }}'">
                                </span>
                            </div>

                        </div>

                        <div class="flex items-center">
                            <span class="material-icons me-1 text-base text-gray-400 dark:text-gray-500">people</span>
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-200"
                                x-text="trip.current_people + '/' + trip.max_people">
                            </span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- 空狀態 -->
            <template x-if="trips.length === 0">
                <div class="text-center py-12">
                    <div class="material-icons text-4xl text-gray-300 dark:text-gray-600 mb-4">directions_car</div>
                    <div class="text-gray-500 dark:text-gray-400 text-base mb-2">
                        {{ __('No trips available for this date') }}
                    </div>
                    <div class="text-gray-400 dark:text-gray-500 text-sm">
                        {{ __('Try selecting a different date or create a new trip') }}
                    </div>
                </div>
            </template>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</x-app-layout>
