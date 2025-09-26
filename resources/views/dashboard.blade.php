@section('Title', 'Dashboard')
<x-app-layout>

    @php
        $dates = collect($groupedTrips->keys())->sort()->take(7); // 增加到7天
        $activeDate = request('date') ?? ($dates->first() ?? null);
    @endphp

    <div x-data="{
        activeDate: '{{ $activeDate }}',
        dates: {{ json_encode($dates->values()) }},
        groupedTrips: {{ json_encode($groupedTrips) }},
        currentIndex: {{ $dates->search($activeDate) ?: 0 }},
        get trips() {
            return this.groupedTrips[this.activeDate] || [];
        },
        selectDate(date, index) {
            this.activeDate = date;
            this.currentIndex = index;
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

        <!-- Carousel 日期選擇器 -->
        <div class="relative px-4 pt-4 pb-2">
            <div id="location-picker"
                class="flex items-center text-sm mt-4 bg-white dark:bg-gray-800 rounded-xl p-2 shadow-md border border-gray-100 dark:border-gray-700 cursor-pointer transition-all hover:shadow-lg hover:scale-[1.02] active:scale-98"
                @click="window.location='{{ route('map') }}'">
                <i class="text-gray-400 dark:text-gray-500 material-icons" id="location_pin">&#xe1b7;</i>
                <span class="ms-2 {{ session('location') == null ? 'italic text-gray-400 dark:text-gray-500' : 'text-gray-900 dark:text-gray-100' }}" id="pickup_location">{{ session('location') ?? __('Pick your location...') }}</span>
            </div>
            
            <div class="flex gap-4 overflow-x-auto no-scrollbar snap-x snap-mandatory mt-6" 
                 style="-webkit-overflow-scrolling: touch; touch-action: pan-x;">
                <template x-for="(date, index) in dates" :key="date">
                    <button
                        @click="selectDate(date, index); $el.scrollIntoView({behavior: 'smooth', inline: 'center', block: 'nearest'})"
                        :class="activeDate === date ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-lg scale-105' :
                            'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                        class="min-w-[70px] px-3 py-3 rounded-lg font-semibold text-sm transition-all duration-300 snap-center focus:outline-none whitespace-nowrap border border-gray-200 dark:border-gray-600">
                        <div class="w-full" x-text="new Date(date).toLocaleDateString('en', {weekday: 'short'})"></div>
                        <div class="w-full"
                            x-text="new Date(date).toLocaleDateString('en', {month: 'short', day: 'numeric'})"></div>
                    </button>
                </template>
            </div>
        </div>
        <!-- Trip 列表 -->
        <div class="flex flex-col gap-4 px-4 py-4">
            <template x-for="trip in trips" :key="trip.id">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 cursor-pointer transition-all hover:shadow-lg hover:scale-[1.02] active:scale-98"
                    @click="window.location='/trips/' + trip.id">
                    <!-- 上方：時間和價格 -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100"
                            x-text="trip.formatted_departure_time">
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400"
                                x-text="'HK$ ' + trip.price">
                            </div>
                        </div>
                    </div>

                    <!-- 中間：地點 -->
                    <div class="flex items-center mb-4">
                        <div class="flex-1">
                            <div class="text-lg font-semibold text-gray-700 dark:text-gray-300"
                                x-text="trip.dropoff_location || '{{ __('Huafa') }}'">
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                            <span class="material-icons text-sm">person</span>
                            <span class="text-sm font-medium" x-text="trip.current_people"></span>
                        </div>
                    </div>

                    <!-- 下方：剩餘時間 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-icons text-gray-400 dark:text-gray-500 text-sm">schedule</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400"
                                x-text="trip.remaining_time || '{{ __('Not Started') }}'">
                            </span>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ __('Click to join') }}
                        </div>
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