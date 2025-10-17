<x-app-layout>
    <div class="min-h-screen">

        <x-slot name="header">
            <!-- Header Section -->
            <div class="py-2">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('My Trips') }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage your accepted trips') }}</p>
                    </div>

                    <!-- Driver Info Card -->
                    {{-- <div class="bg-secondary dark:bg-secondary-accent rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700 min-w-64">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold">{{ strtoupper(substr(Auth::user()->username, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->username }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                                @if (Auth::user()->phone)
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
                    </div> --}}
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
                            // 簡化狀態流程：awaiting -> departed -> completed
                            $tripStatusClasses = [
                                'awaiting' =>
                                    'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200',
                                'departed' => 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200',
                                'completed' => 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200',
                                'cancelled' => 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200',
                            ];

                            // 直接使用 trip_status，不再考慮司機 status
                            $displayStatus = $trip->trip_status;
                            $statusClass = $tripStatusClasses[$displayStatus] ?? $tripStatusClasses['awaiting'];

                            // 司機分配狀態說明
                            $assignmentStatusText = [
                                'assigned' => '已分配（待確認）',
                                'confirmed' => '已確認接受',
                            ];
                        @endphp

                        @if (!$assignment)
                            @continue
                        @endif

                        {{-- 調試信息 --}}
                        @if (config('app.debug'))
                            <!-- Debug: Assignment ID: {{ $assignment->id ?? 'NULL' }}, Driver ID: {{ $assignment->driver_id ?? 'NULL' }} -->
                        @endif

                        <div
                            class="bg-secondary dark:bg-secondary-accent rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 transition-all hover:shadow-lg">
                            <!-- 上方：時間和狀態 -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $trip->planned_departure_time->format('H:i') }}
                                </div>
                                <div class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                    {{ ucfirst($displayStatus) }}
                                </div>
                            </div>

                            <!-- 中間：地點和創建者 -->
                            <span class="text-lg -m-1">📍</span>
                            <span class="ps-1 text-lg font-semibold text-gray-700 dark:text-gray-300">
                                {{ $trip->dropoff_location }}
                            </span>

                            <div class="flex items-center mt-1">
                                <span class="text-lg -m-1">👤</span>
                                <span class="ms-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Created by') }}: {{ $trip->creator->username }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center mt-1">
                                <div class="flex items-center">
                                    <span class="material-icons text-gray-400 dark:text-gray-500 text-lg">schedule</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 ms-2">
                                        {{ $trip->planned_departure_time->format('Y-m-d') }}
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

                            
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex gap-2">
                                    @if ($assignment->status === 'confirmed')
                                        @if ($trip->trip_status === 'awaiting')
                                            <!-- 開始接客：標記出發 -->
                                            <form id="depart-form-{{ $assignment->id }}"
                                                action="{{ route('driver.depart-trip', $assignment) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="button"
                                                    onclick="showConfirmModal({
                                                            title: '🚀 {{ __('Start Pickup') }}',
                                                            message: '{{ __('Start picking up passengers?') }}<br><small class=\'text-gray-500\'>{{ __('Please ensure you have arrived at the first pickup point') }}</small>',
                                                            confirmText: '{{ __('Start Pickup') }}',
                                                            cancelText: '{{ __('Cancel') }}',
                                                            onConfirm: () => document.getElementById('depart-form-{{ $assignment->id }}').submit()
                                                        })"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                                    🚀 {{ __('Start Pickup') }}
                                                </button>
                                            </form>
                                        @elseif ($trip->trip_status === 'departed')
                                            <!-- 到達目的地：完成行程 -->
                                            <form id="complete-form-{{ $assignment->id }}"
                                                action="{{ route('driver.complete-trip', $assignment) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="button"
                                                    onclick="showConfirmModal({
                                                            title: '🏁 {{ __('Complete Trip') }}',
                                                            message: '{{ __('Confirm you have arrived at the destination?') }}<br><small class=\'text-gray-500\'>{{ __('This will mark the trip as completed') }}</small>',
                                                            confirmText: '{{ __('Complete Trip') }}',
                                                            cancelText: '{{ __('Cancel') }}',
                                                            onConfirm: () => document.getElementById('complete-form-{{ $assignment->id }}').submit()
                                                        })"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                                    🏁 {{ __('Arrive Destination') }}
                                                </button>
                                            </form>
                                        @endif

                                        <!-- 取消按鈕：只有 awaiting 狀態才能取消，一旦 departed 就不能取消 -->
                                        @if ($trip->trip_status === 'awaiting')
                                            <form id="cancel-form-{{ $assignment->id }}"
                                                action="{{ route('driver.cancel-trip', $assignment) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="button"
                                                    onclick="showConfirmModal({
                                                            title: '❌ {{ __('Cancel Assignment') }}',
                                                            message: '{{ __('Cancel your assignment to this trip?') }}<br><small class=\'text-gray-500\'>{{ __('The trip will become available for other drivers.') }}</small>',
                                                            confirmText: '{{ __('Cancel Assignment') }}',
                                                            cancelText: '{{ __('Keep Assignment') }}',
                                                            onConfirm: () => document.getElementById('cancel-form-{{ $assignment->id }}').submit()
                                                        })"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                                    ❌ {{ __('Cancel Assignment') }}
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <!-- 顯示司機分配狀態 -->
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-sm font-medium {{ $assignment->status === 'confirmed' ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                                {{ $assignmentStatusText[$assignment->status] ?? ucfirst($assignment->status) }}
                                            </span>
                                        </div>
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
                        class="bg-secondary dark:bg-secondary-accent rounded-xl p-12 shadow-md border border-gray-100 dark:border-gray-700 text-center">
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
