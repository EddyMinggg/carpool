@props(['initialLocation' => null])

<div x-data="locationSelector({{ $initialLocation ? "'" . $initialLocation . "'" : 'null' }})" 
     class="location-selector">
    
    <!-- 當前選中的地址顯示 -->
    <div class="flex items-center text-sm mt-4 mb-4">
        <i class="text-gray-400 dark:text-gray-500 material-icons" :class="{'text-green-500': selectedLocation}">
            <span x-text="selectedLocation ? 'location_on' : 'location_searching'"></span>
        </i>
        <div class="ml-2 flex-1">
            <div x-show="!selectedLocation && !showSearchBox && locationOptions.length === 0" 
                 class="text-gray-500 dark:text-gray-400 cursor-pointer"
                 @click="showSearchBox = true">
                點擊選擇接送地點
            </div>
            <div x-show="selectedLocation" class="text-gray-900 dark:text-gray-100">
                <div class="font-medium" x-text="selectedLocation?.name"></div>
                <div class="text-xs text-gray-500" x-text="selectedLocation?.formatted_address"></div>
            </div>
        </div>
        <button x-show="selectedLocation" 
                @click="editLocation()" 
                class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400">
            <i class="material-icons text-sm">edit</i>
        </button>
    </div>

    <!-- 當前位置選項列表 -->
    <div x-show="locationOptions.length > 0" class="mb-4">
        <div class="text-sm text-gray-700 dark:text-gray-300 mb-2 font-medium">請選擇最合適的地點：</div>
        <div class="space-y-2 max-h-64 overflow-y-auto">
            <template x-for="(option, index) in locationOptions" :key="option.place_id">
                <button @click="selectLocationOption(option)"
                        class="w-full text-left p-3 border border-gray-200 dark:border-gray-600 rounded-lg 
                               hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-300 transition-colors">
                    <div class="flex items-start space-x-3">
                        <i class="material-icons text-gray-400 mt-0.5 text-base">place</i>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 dark:text-gray-100" 
                                 x-text="option.name || option.formatted_address"></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1" 
                                 x-text="option.formatted_address"></div>
                            <div x-show="option.types" class="flex flex-wrap gap-1 mt-2">
                                <template x-for="type in (option.types || []).slice(0, 3)" :key="type">
                                    <span class="inline-block px-2 py-0.5 text-xs bg-gray-100 dark:bg-gray-600 
                                               text-gray-600 dark:text-gray-300 rounded-full" 
                                          x-text="getTypeLabel(type)"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </button>
            </template>
        </div>
        <div class="mt-3 flex justify-between">
            <button @click="locationOptions = []" 
                    class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                取消
            </button>
            <button @click="useCurrentLocation()" 
                    class="px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">
                重新獲取位置
            </button>
        </div>
    </div>

    <!-- 搜索框和建議列表 -->
    <div x-show="showSearchBox" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        <!-- 搜索輸入框 -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="relative">
                <input type="text" 
                    x-model="searchQuery"
                    @keyup.debounce.300ms="searchAddresses()"
                    @focus="showSuggestions = true"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="輸入地址搜索..."
                >
                
                <!-- 載入指示器 -->
                <div x-show="isLoading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                </div>
            </div>
            
            <!-- 錯誤信息 -->
            <div x-show="errorMessage" class="mt-2 text-red-600 text-sm" x-text="errorMessage"></div>
        </div>

        <!-- 搜索建議列表 -->
        <div x-show="suggestions.length > 0" class="max-h-64 overflow-y-auto">
            <template x-for="(place, index) in suggestions" :key="place.place_id">
                <button @click="selectPlace(place)" 
                        class="w-full px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-gray-700 
                               border-b border-gray-100 dark:border-gray-600 last:border-b-0
                               focus:bg-gray-50 dark:focus:bg-gray-700 focus:outline-none">
                    <div class="flex items-start space-x-3">
                        <i class="material-icons text-gray-400 mt-0.5 text-base">place</i>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 dark:text-gray-100 truncate" 
                                 x-text="place.name"></div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate" 
                                 x-text="place.formatted_address"></div>
                            <div x-show="place.types" class="flex flex-wrap gap-1 mt-1">
                                <template x-for="type in (place.types || []).slice(0, 2)" :key="type">
                                    <span class="inline-block px-2 py-0.5 text-xs bg-gray-100 dark:bg-gray-600 
                                               text-gray-600 dark:text-gray-300 rounded-full" 
                                          x-text="getTypeLabel(type)"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </button>
            </template>
        </div>

        <!-- 無搜索結果 -->
        <div x-show="searchQuery.length > 2 && suggestions.length === 0 && !isLoading" 
             class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
            <i class="material-icons text-4xl mb-2 opacity-50">search_off</i>
            <div>找不到相關地點</div>
            <div class="text-sm mt-1">請嘗試其他關鍵字</div>
        </div>

        <!-- 操作按鈕 -->
        <div class="p-4 bg-gray-50 dark:bg-gray-700 flex justify-between">
            <button @click="useCurrentLocation()" 
                    :disabled="isGettingLocation"
                    class="flex items-center space-x-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 
                           dark:bg-gray-600 dark:hover:bg-gray-500 rounded-lg text-sm
                           disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="material-icons text-base" x-text="isGettingLocation ? 'hourglass_empty' : 'my_location'"></i>
                <span x-text="isGettingLocation ? '獲取中...' : '使用當前位置'"></span>
            </button>
            
            <button @click="showSearchBox = false; suggestions = []; searchQuery = ''" 
                    class="px-4 py-2 text-gray-600 dark:text-gray-400 text-sm hover:text-gray-800 dark:hover:text-gray-200">
                取消
            </button>
        </div>
    </div>

    <!-- 常用地點 -->
    <div x-show="!showSearchBox && !selectedLocation && locationOptions.length === 0" class="mt-4">
        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">常用地點</div>
        <div class="grid grid-cols-2 gap-2">
            <template x-for="place in favoriteePlaces" :key="place.id">
                <button @click="selectPlace(place)" 
                        class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg text-left hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    <div class="font-medium text-sm text-gray-900 dark:text-gray-100" x-text="place.name"></div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="place.formatted_address"></div>
                </button>
            </template>
        </div>
    </div>
</div>

<script>
function locationSelector(initialLocation) {
    return {
        selectedLocation: initialLocation ? JSON.parse(initialLocation) : null,
        showSearchBox: false,
        searchQuery: '',
        suggestions: [],
        isLoading: false,
        showSuggestions: false,
        isSearchFocused: false,
        isGettingLocation: false,
        searchTimeout: null,
        errorMessage: '',
        locationOptions: [], // 當前位置的多個選項
        favoriteePlaces: [
            // 常用香港地點
            {
                id: 'hk_airport',
                name: '香港國際機場',
                formatted_address: '香港國際機場, 赤鱲角, 香港',
                place_id: 'ChIJncZGzPPiAzQRnjaSGIKQ9fk',
                geometry: {
                    location: { lat: 22.3134736, lng: 113.9137283 }
                },
                types: ['airport', 'point_of_interest', 'establishment']
            },
            {
                id: 'central_station',
                name: '中環站',
                formatted_address: '中環站, 中環, 香港',
                place_id: 'ChIJv5Jp2cj_AzQRxjBhYS9zE9o',
                geometry: {
                    location: { lat: 22.2814842, lng: 114.1579201 }
                },
                types: ['transit_station', 'point_of_interest', 'establishment']
            },
            {
                id: 'tsim_sha_tsui',
                name: '尖沙咀',
                formatted_address: '尖沙咀, 香港',
                place_id: 'ChIJoRVZlnb_AzQR5VVCgvAo9fE',
                geometry: {
                    location: { lat: 22.2976431, lng: 114.1718831 }
                },
                types: ['neighborhood', 'political']
            },
            {
                id: 'causeway_bay',
                name: '銅鑼灣',
                formatted_address: '銅鑼灣, 香港',
                place_id: 'ChIJ9zKfF9kBBDQRUvApOBWzx2Y',
                geometry: {
                    location: { lat: 22.2798811, lng: 114.1821839 }
                },
                types: ['neighborhood', 'political']
            }
        ],

        init() {
            // 如果有選中的地址，通知父組件
            if (this.selectedLocation) {
                this.notifyLocationChange();
            }
        },

        editLocation() {
            this.showSearchBox = true;
            this.searchQuery = '';
            this.suggestions = [];
            this.locationOptions = [];
        },

        // 搜索地址
        async searchAddresses() {
            if (this.searchQuery.length < 2) {
                this.suggestions = [];
                this.errorMessage = '';
                return;
            }

            this.isLoading = true;
            this.errorMessage = '';
            
            try {
                const response = await fetch(`/api/places/test-search?q=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();
                
                if (data.success) {
                    this.suggestions = data.data || [];
                    if (this.suggestions.length === 0) {
                        this.errorMessage = '找不到相關地址，請嘗試其他關鍵字';
                    }
                } else {
                    console.error('搜索失敗:', data.message);
                    this.errorMessage = data.message || '搜索地址時發生錯誤';
                    this.suggestions = [];
                }
            } catch (error) {
                console.error('搜索地址時發生錯誤:', error);
                this.errorMessage = '無法連接到地址搜索服務，請檢查網絡連接';
                this.suggestions = [];
            } finally {
                this.isLoading = false;
            }
        },

        selectPlace(place) {
            this.selectedLocation = {
                place_id: place.place_id,
                name: place.name,
                formatted_address: place.formatted_address,
                geometry: place.geometry,
                types: place.types
            };
            
            this.showSearchBox = false;
            this.searchQuery = '';
            this.suggestions = [];
            this.locationOptions = [];
            
            // 通知父組件地址已更改
            this.notifyLocationChange();
        },

        // 選擇當前位置選項
        selectLocationOption(option) {
            this.selectPlace(option);
        },

        async useCurrentLocation() {
            if (!navigator.geolocation) {
                alert('您的瀏覽器不支持地理定位功能');
                return;
            }

            this.isGettingLocation = true;
            this.locationOptions = [];
            this.errorMessage = '';

            const options = {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000 // 5分鐘緩存
            };

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    try {
                        // 使用反向地理編碼獲取多個地址選項
                        const response = await fetch(`/api/places/test-reverse-geocode?lat=${position.coords.latitude}&lng=${position.coords.longitude}`);
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('API 返回非 JSON 響應:', text);
                            throw new Error('API 返回格式錯誤');
                        }
                        
                        const data = await response.json();
                        console.log('反向地理編碼響應:', data);
                        
                        if (Array.isArray(data.results) && data.results.length > 0) {
                            this.locationOptions = data.results;
                            this.showSearchBox = false;
                        } else {
                            throw new Error('無法獲取地址信息');
                        }
                    } catch (error) {
                        console.error('獲取當前位置詳細地址失敗:', error);
                        this.errorMessage = '無法獲取當前位置的詳細地址: ' + error.message;
                    } finally {
                        this.isGettingLocation = false;
                    }
                },
                (error) => {
                    this.isGettingLocation = false;
                    let errorMessage = '獲取位置失敗';
                    
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = '位置訪問被拒絕，請在瀏覽器設置中允許位置訪問';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = '位置信息不可用';
                            break;
                        case error.TIMEOUT:
                            errorMessage = '獲取位置超時';
                            break;
                    }
                    
                    this.errorMessage = errorMessage;
                },
                options
            );
        },

        notifyLocationChange() {
            // 觸發自定義事件，通知父組件
            this.$dispatch('location-selected', {
                location: this.selectedLocation
            });

            // 同時更新 session
            if (this.selectedLocation) {
                this.updateSession();
            }
        },

        async updateSession() {
            try {
                await fetch('/set-session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        location: this.selectedLocation.formatted_address,
                        location_details: this.selectedLocation
                    })
                });
            } catch (error) {
                console.error('更新 session 失敗:', error);
            }
        },

        getTypeLabel(type) {
            const typeLabels = {
                'establishment': '地點',
                'point_of_interest': '景點',
                'store': '商店',
                'restaurant': '餐廳',
                'transit_station': '交通站',
                'shopping_mall': '購物中心',
                'school': '學校',
                'hospital': '醫院',
                'bank': '銀行',
                'gas_station': '加油站',
                'neighborhood': '區域',
                'sublocality': '地區',
                'political': '行政區',
                'street_address': '街道地址',
                'premise': '建築物',
                'subpremise': '單位'
            };
            
            return typeLabels[type] || type;
        }
    }
}
</script>
