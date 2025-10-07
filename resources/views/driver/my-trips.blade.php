<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">

        <x-slot name="header">
            <!-- Header Section -->
            <div class="py-2">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('My Trips') }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage your accepted trips') }}</p>
                    </div>
                    
                    <!-- Driver Info Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700 min-w-64">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold">{{ strtoupper(substr(Auth::user()->username, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->username }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                                @if(Auth::user()->phone)
                                    <p class="text-xs text-gray-400 dark:text-gray-500">📞 {{ Auth::user()->phone }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Status') }}</span>
                                <span class="text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded-full">
                                    🚗 {{ __('Active Driver') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Summary -->
                <div class="mt-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900/50 p-4 rounded-xl text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ $stats['my_active'] }}
                            </div>
                            <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ __('Active Trips') }}
                            </div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/50 p-4 rounded-xl text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ $stats['my_completed'] }}
                            </div>
                            <div class="text-sm text-green-600 dark:text-green-400 font-medium">{{ __('Completed') }}
                            </div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/50 p-4 rounded-xl text-center">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                {{ $stats['my_cancelled'] }}
                            </div>
                            <div class="text-sm text-red-600 dark:text-red-400 font-medium">{{ __('Cancelled') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>

        <!-- My Trips Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col gap-4 mt-4">
                @if ($assignedTrips->count() > 0)
                    @foreach ($assignedTrips as $trip)
                        @php
                            $assignment = $trip->tripDriver;
                            $statusClasses = [
                                'assigned' =>
                                    'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200',
                                'confirmed' => 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200',
                                'completed' =>
                                    'bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200',
                                'cancelled' => 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200',
                            ];
                        @endphp

                        @if (!$assignment)
                            @continue
                        @endif

                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 transition-all hover:shadow-lg">
                            <!-- 上方：時間和狀態 -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $trip->planned_departure_time->format('H:i') }}
                                </div>
                                <div
                                    class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$assignment->status] ?? $statusClasses['assigned'] }}">
                                    {{ ucfirst($assignment->status) }}
                                </div>
                            </div>

                            <!-- 中間：地點和創建者 -->
                            <span class="text-lg -m-1">📍</span>
                            <span class="ps-1 text-lg font-semibold text-gray-700 dark:text-gray-300">
                                {{ $trip->dropoff_location }}
                            </span>

                            <div class="flex justify-between items-center mt-1 mb-4">
                                <div>
                                    <span class="text-lg -m-1">👤</span>
                                    <span class="ps-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Created by') }}: {{ $trip->creator->username }}
                                    </span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                                        <span class="material-icons text-sm">person</span>
                                        <span
                                            class="text-sm font-medium">{{ $trip->joins->count() }}/{{ $trip->max_people }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 下方：日期和操作 -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="material-icons text-gray-400 dark:text-gray-500 text-lg">schedule</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $trip->planned_departure_time->format('Y-m-d') }}
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    @if ($assignment->status === 'confirmed')
                                        <!-- Trip is confirmed and ready for completion -->
                                        <form action="{{ route('driver.complete-trip', $assignment) }}" method="POST"
                                            onsubmit="return confirm(@js(__('Mark as completed?')));" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                                🏁 {{ __('Complete') }}
                                            </button>
                                        </form>

                                        <form action="{{ route('driver.cancel-trip', $assignment) }}" method="POST"
                                            onsubmit="return confirm(@js(__('Cancel this trip?')));" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                                ❌ {{ __('Cancel') }}
                                            </button>
                                        </form>
                                    @elseif($assignment->status === 'completed')
                                        <span class="text-sm text-green-600 dark:text-green-400 font-medium">
                                            ✅ {{ __('Completed') }}
                                        </span>
                                    @elseif($assignment->status === 'cancelled')
                                        <span class="text-sm text-red-600 dark:text-red-400 font-medium">
                                            ❌ {{ __('Cancelled') }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Status') }}: {{ ucfirst($assignment->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $assignedTrips->links() }}
                    </div>
                @else
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-12 shadow-md border border-gray-100 dark:border-gray-700 text-center">
                        <div class="text-6xl mb-4">📋</div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('No Assigned Trips') }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            {{ __('You have not accepted any trips yet') }}</p>
                        <a href="{{ route('driver.dashboard') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            {{ __('Browse Available Trips') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
            x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
            x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
            {{ session('error') }}
        </div>
    @endif
</x-app-layout>
