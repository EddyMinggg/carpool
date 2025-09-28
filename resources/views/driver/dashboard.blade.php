<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Driver Dashboard') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Select and manage your trips') }}</p>
            </div>
        </div>

        <!-- Available Trips Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Available Trips') }}</h2>
            </div>
            
            <div class="flex flex-col gap-4">
                @if($availableTrips->count() > 0)
                    @foreach($availableTrips as $trip)
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 transition-all hover:shadow-lg">
                            <!-- 上方：時間和ID -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $trip->planned_departure_time->format('H:i') }}
                                </div>
                                <div class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                                    #{{ $trip->id }}
                                </div>
                            </div>

                            <!-- 中間：地點和創建者 -->
                            <div class="flex items-center mb-4">
                                <div class="flex-1">
                                    <div class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                                        📍 {{ $trip->dropoff_location }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        👤 {{ __('Created by') }}: {{ $trip->creator->username }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                                    <span class="material-icons text-sm">person</span>
                                    <span class="text-sm font-medium">{{ $trip->joins->count() }}/{{ $trip->max_people }}</span>
                                </div>
                            </div>

                            <!-- 下方：日期和操作 -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-icons text-gray-400 dark:text-gray-500 text-sm">schedule</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $trip->planned_departure_time->format('Y-m-d') }}
                                    </span>
                                </div>
                                <form action="{{ route('driver.assign-trip', $trip) }}" method="POST" onsubmit="return confirm(@js(__('Confirm to assign this trip?')));">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        🚗 {{ __('Accept Trip') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $availableTrips->links() }}
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-12 shadow-md border border-gray-100 dark:border-gray-700 text-center">
                        <div class="text-6xl mb-4">🚗</div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('No Available Trips') }}</h3>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('All trips have been assigned or completed') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" 
             x-data="{ show: true }" 
             x-show="show" 
             x-transition 
             x-init="setTimeout(() => show = false, 3000)">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" 
             x-data="{ show: true }" 
             x-show="show" 
             x-transition 
             x-init="setTimeout(() => show = false, 3000)">
            {{ session('error') }}
        </div>
    @endif
</x-app-layout>