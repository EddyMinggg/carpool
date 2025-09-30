<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">

        <x-slot name="header">
            <!-- Header Section -->
            <div class="py-2">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('My Trips') }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage your accepted trips') }}</p>
                </div>

                <!-- Statistics Summary -->
                <div class="mt-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-yellow-50 dark:bg-yellow-900/50 p-4 rounded-xl text-center">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ $stats['my_assigned'] }}
                            </div>
                            <div class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">{{ __('Assigned') }}
                            </div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/50 p-4 rounded-xl text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ $stats['my_confirmed'] }}
                            </div>
                            <div class="text-sm text-green-600 dark:text-green-400 font-medium">{{ __('Confirmed') }}
                            </div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/50 p-4 rounded-xl text-center">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ $stats['my_completed'] }}</div>
                            <div class="text-sm text-purple-600 dark:text-purple-400 font-medium">{{ __('Completed') }}
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
                            $assignment = \App\Models\TripDriver::find($trip->id);
                            $statusClasses = [
                                'assigned' =>
                                    'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200',
                                'confirmed' => 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200',
                                'completed' =>
                                    'bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200',
                                'cancelled' => 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200',
                            ];
                        @endphp

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
                                    @if ($assignment->status === 'assigned')
                                        <form action="{{ route('driver.confirm-trip', $assignment) }}" method="POST"
                                            onsubmit="return confirm(@js(__('Confirm this trip?')));" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                                ✅ {{ __('Confirm') }}
                                            </button>
                                        </form>

                                        <form action="{{ route('driver.cancel-trip', $assignment) }}" method="POST"
                                            onsubmit="return confirm(@js(__('Cancel this assignment?')));" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                                ❌ {{ __('Cancel') }}
                                            </button>
                                        </form>
                                    @elseif($assignment->status === 'confirmed')
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
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Trip') }} {{ $assignment->status }}
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
