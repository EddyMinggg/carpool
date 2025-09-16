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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        
        <!-- 消息顯示 -->
        @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show" x-transition>
            {{ session('success') }}
            <button @click="show = false" class="float-right text-green-500 hover:text-green-700 ml-2">&times;</button>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show" x-transition>
            {{ session('error') }}
            <button @click="show = false" class="float-right text-red-500 hover:text-red-700 ml-2">&times;</button>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show" x-transition>
            <ul>
                @foreach($errors->all() as $error)
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
                {{-- <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600 dark:text-gray-300">{{ __('Destination') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $trip->dropoff_location }}</span>
                </div> --}}
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600 dark:text-gray-300">{{ __('Joined User') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $currentPeople }}/{{ $trip->max_people }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-300">{{ __('Status') }}</span>
                    <span class="px-2 py-1 rounded text-sm
                        @if($trip->trip_status === 'awaiting') bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200
                        @elseif($trip->trip_status === 'voting') bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200
                        @elseif($trip->trip_status === 'departed') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200
                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif">
                        {{ ucfirst($trip->trip_status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 倒計時區域 -->
        @if($timeUntilDeparture > 0)
        <div class="bg-gradient-to-r from-orange-400 dark:from-orange-500 to-red-500 dark:to-red-600 text-white rounded-xl p-4 text-center shadow-md">
            <div class="text-sm mb-1">{{ __('Auto departure in') }}</div>
            <div class="text-2xl font-bold">{{ floor($timeUntilDeparture / 60) }}:{{ str_pad($timeUntilDeparture % 60, 2, '0', STR_PAD_LEFT) }}</div>
        </div>
        @endif
        <!-- 成員列表 -->
        @if ($trip->joins->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">{{ __('Members') }}</h3>
            @foreach($trip->joins as $join)
            <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 dark:text-blue-300 font-semibold text-sm">{{ substr($join->user->username, 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $join->user->username }}</div>
                        @if($join->pickup_location)
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $join->pickup_location }}</div>
                        @endif
                    </div>
                </div>
                <div class="text-sm">
                    @if($join->user_id === auth()->id())
                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded text-xs">
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

        <!-- 投票狀態 -->
        @if($currentVote)
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-700/50 shadow-md">
            <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-2">{{ __('Voting in Progress') }}</h3>
            <p class="text-sm text-blue-600 dark:text-blue-400 mb-4">{{ __('Should we depart immediately?') }}</p>
            
            @if($userVoteStatus === 'pending')
            <div class="flex gap-3">
                <form action="{{ route('trips.vote', $trip) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="vote_result" value="agree">
                    <button type="submit" class="w-full py-4 rounded-lg font-semibold transition text-white" 
                            style="background-color: #22c55e !important;" 
                            onmouseover="this.style.backgroundColor='#16a34a'" 
                            onmouseout="this.style.backgroundColor='#22c55e'">
                        {{ __('Agree') }}
                    </button>
                </form>
                <form action="{{ route('trips.vote', $trip) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="vote_result" value="disagree">
                    <button type="submit" class="w-full py-4 rounded-lg font-semibold transition text-white" 
                            style="background-color: #ef4444 !important;" 
                            onmouseover="this.style.backgroundColor='#dc2626'" 
                            onmouseout="this.style.backgroundColor='#ef4444'">
                        {{ __('Disagree') }}
                    </button>
                </form>
            </div>
            @else
            <div class="text-center py-2">
                @if($userVoteStatus === 'agree')
                    <span class="px-4 py-2 rounded-lg font-semibold text-white" style="background-color: #22c55e !important;">
                        {{ __('You voted:') }} {{ __('Agree') }}
                    </span>
                @else
                    <span class="px-4 py-2 rounded-lg font-semibold text-white" style="background-color: #ef4444 !important;">
                        {{ __('You voted:') }} {{ __('Disagree') }}
                    </span>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- 操作按鈕 -->
        <div class="space-y-6">
            @if(!$hasJoined)
                <!-- 加入拼車表單 -->
                <form action="{{ route('trips.join', $trip) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        {{-- <label for="pickup_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Pickup Location') }}
                        </label>
                        <input type="text" 
                               name="pickup_location" 
                               id="pickup_location"
                               placeholder="{{ __('Enter your pickup location...') }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"> --}}
                        
                               {{-- <x-input-label for="pickup_location" :value="__('Pickup Location')" />
                        <x-text-input id="pickup_location" class="block mt-2 w-full" type="text"
                            name="pickup_location" :value="old('pickup_location')" required disabled />
                        
                            <x-input-error :messages="$errors->get('pickup_location')" class="mt-2" /> --}}
                       


                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white py-4 rounded-xl font-semibold text-lg transition shadow-md">
                        {{ __('Join') }} - HK$ {{ number_format($price, 0) }}
                    </button>
                </form>
            @else
                @if(!$currentVote && $trip->trip_status === 'pending')
                    @php
                        $memberCount = $trip->joins->count();
                    @endphp
                    
                    @if($memberCount === 1)
                        <!-- 只有一個人時，可以立即出發 -->
                        <form action="{{ route('trips.depart-now', $trip) }}" method="POST" onsubmit="return confirm('Are you sure you want to depart now?');">
                            @csrf
                            <button type="submit" class="w-full py-4 rounded-xl font-semibold transition shadow-md text-white" 
                                    style="background-color: #2563eb;" 
                                    onmouseover="this.style.backgroundColor='#1d4ed8'" 
                                    onmouseout="this.style.backgroundColor='#2563eb'">
                                {{ __('Depart Now') }}
                            </button>
                        </form>
                    @else
                        <!-- 多人時只能投票 -->
                        <form action="{{ route('trips.start-vote', $trip) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 rounded-xl font-semibold transition shadow-md text-white" 
                                    style="background-color: #2563eb;" 
                                    onmouseover="this.style.backgroundColor='#1d4ed8'" 
                                    onmouseout="this.style.backgroundColor='#2563eb'">
                                {{ __('Start Vote to Depart') }}
                            </button>
                        </form>
                    @endif
                @endif
                
                <!-- 離開拼車表單 - 所有用戶都可以離開 -->
                <div class="mt-8">
                    <form action="{{ route('trips.leave', $trip) }}" method="POST" onsubmit="return confirm('Are you sure you want to leave this trip?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white py-4 rounded-xl font-semibold transition shadow-md border border-red-300 dark:border-red-500">
                            {{ __('Leave Carpool') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- 分享按鈕 -->
        <div class="flex gap-3 mt-4">
            <button class="flex-1 py-3 rounded-xl font-semibold flex items-center justify-center gap-3 transition shadow-md text-white" 
                    style="background-color: #25D366;" 
                    onmouseover="this.style.backgroundColor='#1DA851'" 
                    onmouseout="this.style.backgroundColor='#25D366'">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.787"/>
                </svg>
                {{ __('Whatsapp Share') }}
            </button>
            <button class="flex-1 py-3 rounded-xl font-semibold flex items-center justify-center gap-3 transition shadow-md text-white" 
                    style="background-color: #6b7280;" 
                    onmouseover="this.style.backgroundColor='#4b5563'" 
                    onmouseout="this.style.backgroundColor='#6b7280'">
                <span class="material-icons text-sm">link</span>
                {{ __('Copy Link') }}
            </button>
        </div>
    </div>
</x-app-layout>
