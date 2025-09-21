<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 pb-12">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-500 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Trip Details Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 dark:from-blue-600 dark:to-purple-700 p-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-xl font-bold">
                            {{ \Carbon\Carbon::parse($trip->planned_departure_time)->format('H:i') }}
                        </h1>
                        <p class="text-blue-100 text-sm">
                            {{ \Carbon\Carbon::parse($trip->planned_departure_time)->format('Y-m-d') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">HK$ {{ number_format($price) }}</div>
                        <div class="text-blue-100 text-sm">{{ __('Per person') }}</div>
                    </div>
                </div>
            </div>

            <!-- Trip Info -->
            <div class="p-6 space-y-6">
                <!-- Destination -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Destination') }}</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $trip->dropoff_location }}</p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Current People') }}</h4>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $currentPeople }}/{{ $trip->max_people }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</h4>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            @if($trip->trip_status === 'awaiting') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400
                            @elseif($trip->trip_status === 'departed') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                            @elseif($trip->trip_status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                            @elseif($trip->trip_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                            @endif">
                            {{ ucfirst($trip->trip_status) }}
                        </span>
                    </div>
                </div>

                <!-- Members -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">{{ __('Members') }}</h3>
                    <div class="space-y-3">
                        @foreach($trip->joins as $join)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr($join->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            @if($join->user->id === auth()->id())
                                                {{ __('You') }}
                                            @else
                                                {{ $join->user->name }}
                                            @endif
                                        </p>
                                        @if($join->pickup_location)
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $join->pickup_location }}</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                    {{ __('Member') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-6">
                    @if(!$userHasJoined && $trip->trip_status === 'awaiting' && $currentPeople < $trip->max_people)
                        <!-- Join Form -->
                        <form action="{{ route('trips.join', $trip) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="pickup_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Pickup Location (Optional)') }}
                                </label>
                                <input type="text" 
                                       id="pickup_location" 
                                       name="pickup_location" 
                                       placeholder="{{ __('Enter your pickup location...') }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                            </div>
                            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition duration-200">
                                {{ __('Join') }} - HK$ {{ number_format($price) }}
                            </button>
                        </form>
                    @endif

                    @if($userHasJoined && $trip->trip_status === 'awaiting')
                        <!-- Depart Now Button (for single person or immediate departure) -->
                        <form action="{{ route('trips.depart-now', $trip) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full py-4 rounded-xl font-semibold transition text-white" 
                                    style="background-color: #2563eb !important;"
                                    onmouseover="this.style.backgroundColor='#1d4ed8'" 
                                    onmouseout="this.style.backgroundColor='#2563eb'"
                                    onclick="return confirm('Are you sure you want to depart now?')">
                                {{ __('Depart Now') }}
                            </button>
                        </form>
                    @endif

                    @if($userHasJoined)
                        <!-- Leave Button -->
                        <form action="{{ route('trips.leave', $trip) }}" method="POST" class="mt-8">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full py-4 bg-transparent hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 font-semibold rounded-xl border border-red-300 dark:border-red-500 transition duration-200"
                                    onclick="return confirm('Are you sure you want to leave this carpool?')">
                                {{ __('Leave Carpool') }}
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Share Options -->
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <!-- WhatsApp Share -->
                    <a href="https://api.whatsapp.com/send?text={{ urlencode('Join my carpool trip to ' . $trip->dropoff_location . ' on ' . \Carbon\Carbon::parse($trip->planned_departure_time)->format('M d, H:i') . '. Check it out: ' . url()->current()) }}" 
                       target="_blank"
                       class="flex-1 flex items-center justify-center gap-3 py-3 px-4 rounded-xl font-medium text-white transition duration-200"
                       style="background-color: #25D366 !important;"
                       onmouseover="this.style.backgroundColor='#1DA851'"
                       onmouseout="this.style.backgroundColor='#25D366'">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.109"/>
                        </svg>
                        WhatsApp {{ __('Share') }}
                    </a>

                    <!-- Copy Link -->
                    <button onclick="copyToClipboard('{{ url()->current() }}')" 
                            class="flex-1 flex items-center justify-center gap-3 py-3 px-4 rounded-xl font-medium text-white transition duration-200"
                            style="background-color: #6b7280 !important;"
                            onmouseover="this.style.backgroundColor='#4b5563'"
                            onmouseout="this.style.backgroundColor='#6b7280'">
                        <span class="material-icons text-sm">link</span>
                        {{ __('Copy Link') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('{{ __("Link copied to clipboard!") }}');
            });
        }

        // Auto-dismiss messages after 5 seconds
        setTimeout(function() {
            const messages = document.querySelectorAll('.bg-green-100, .bg-red-100');
            messages.forEach(function(message) {
                message.style.transition = 'opacity 0.5s';
                message.style.opacity = '0';
                setTimeout(function() {
                    message.remove();
                }, 500);
            });
        }, 5000);
    </script>
</x-app-layout>