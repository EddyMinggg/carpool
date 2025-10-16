<x-app-layout>
    <div class="min-h-screen">

        <!-- Header Section -->
        <x-slot name="header">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ __('Driver Dashboard') }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Select and manage your trips') }}</p>
                </div>
            </div>
        </x-slot>

        <!-- Stats Cards Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Driver Info & Quick Stats -->
            {{-- <div
                class="bg-secondary dark:bg-secondary-accent rounded-lg p-4 mt-6 shadow-sm border border-gray-200 dark:border-gray-700 min-w-80">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                            <span
                                class="text-white font-semibold text-lg">{{ strtoupper(substr(Auth::user()->username, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->username }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                            @if (Auth::user()->phone)
                                <p class="text-xs text-gray-400 dark:text-gray-500">📞 {{ Auth::user()->phone }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <span
                            class="text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded-full">
                            🚗 {{ __('Active') }}
                        </span>
                    </div>
                </div>

                <!-- Quick Action -->
                <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('driver.my-trips') }}"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition-colors">
                        📋 {{ __('View My Trips') }} →
                    </a>
                </div>
            </div> --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-6">
                <!-- My Active Trips -->
                <div
                    class="bg-secondary dark:bg-secondary-accent rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('My Active Trips') }}
                            </p>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ $myTrips->where('assignment_status', 'confirmed')->count() }}</p>
                        </div>
                        <div class="text-blue-500 text-2xl">🚗</div>
                    </div>
                </div>

                <!-- Completed Trips -->
                <div
                    class="bg-secondary dark:bg-secondary-accent rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Completed') }}</p>
                            <p class="text-2xl font-bold text-green-600">
                                {{ $myTrips->where('assignment_status', 'completed')->count() }}</p>
                        </div>
                        <div class="text-green-500 text-2xl">✅</div>
                    </div>
                </div>

                <!-- Available Trips -->
                <div
                    class="bg-secondary dark:bg-secondary-accent rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Available') }}</p>
                            <p class="text-2xl font-bold text-orange-600">{{ $availableTrips->total() }}</p>
                        </div>
                        <div class="text-orange-500 text-2xl">📋</div>
                    </div>
                </div>

                <!-- This Week -->
                <div
                    class="bg-secondary dark:bg-secondary-accent rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('This Week') }}</p>
                            <p class="text-2xl font-bold text-purple-600">
                                {{ $myTrips->where('created_at', '>=', now()->startOfWeek())->count() }}
                            </p>
                        </div>
                        <div class="text-purple-500 text-2xl">📅</div>
                    </div>
                </div>
            </div>
            <!-- Available Trips Section -->
            <div class="my-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Available Trips') }}</h2>
            </div>

            <div class="flex flex-col gap-4">
                @if ($availableTrips->count() > 0)
                    @foreach ($availableTrips as $trip)
                        <div
                            class="bg-secondary dark:bg-secondary-accent rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700 transition-all hover:shadow-lg">
                            <!-- 上方：時間和ID -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $trip->planned_departure_time->format('H:i') }}
                                </div>
                                <div
                                    class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                                    #{{ $trip->id }}
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
                                <button type="button"
                                    onclick="openConfirmModal({{ $trip->id }}, @js($trip->dropoff_location), @js($trip->planned_departure_time->format('Y-m-d H:i')), @js(route('driver.assign-trip', $trip->id)))"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    🚗 {{ __('Accept Trip') }}
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $availableTrips->links() }}
                    </div>
                @else
                    <div
                        class="bg-secondary dark:bg-secondary-accent rounded-xl p-12 shadow-md border border-gray-100 dark:border-gray-700 text-center">
                        <div class="text-6xl mb-4">🚗</div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('No Available Trips') }}</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            {{ __('All trips have been assigned or completed') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div id="confirmModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-secondary dark:bg-secondary-accent rounded-xl max-w-md w-full mx-4 shadow-2xl transform transition-all">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        🚗 {{ __('Accept Trip') }}
                    </h3>
                    <button onclick="closeConfirmModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl">
                        ×
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        {{ __('Are you sure you want to accept this trip?') }}</p>

                    <!-- Trip Details -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2">
                        <div class="flex items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-16">{{ __('Trip') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100" id="modal-trip-id">#-</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-16">{{ __('To') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100" id="modal-destination">-</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-16">{{ __('Time') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100" id="modal-time">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="p-6 bg-gray-50 dark:bg-gray-700 rounded-b-xl flex space-x-3">
                <button onclick="closeConfirmModal()"
                    class="flex-1 px-4 py-3 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                    {{ __('Cancel') }}
                </button>
                <button onclick="confirmAcceptTrip()"
                    class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    🚗 {{ __('Accept Trip') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden form for trip acceptance -->
    <form id="acceptTripForm" method="POST" style="display: none;">
        @csrf
    </form>

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

    <!-- JavaScript for Modal -->
    <script>
        let currentTripId = null;
        let currentTripUrl = null;

        function openConfirmModal(tripId, destination, time, url) {
            currentTripId = tripId;
            currentTripUrl = url;
            document.getElementById('modal-trip-id').textContent = '#' + tripId;
            document.getElementById('modal-destination').textContent = destination;
            document.getElementById('modal-time').textContent = time;
            document.getElementById('confirmModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentTripId = null;
            currentTripUrl = null;
        }

        function confirmAcceptTrip() {
            if (currentTripId && currentTripUrl) {
                const form = document.getElementById('acceptTripForm');
                form.action = currentTripUrl;
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeConfirmModal();
            }
        });
    </script>
</x-app-layout>
