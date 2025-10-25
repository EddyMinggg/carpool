@section('Title', __('Map'))
<x-app-layout>
    <div id="map" style="height: 100vh; width: 100%;"></div>
    
    <!-- 中心固定標記 -->
    <div id="center-marker" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-full pointer-events-none z-[1000] transition-transform duration-200">
        <div class="flex flex-col items-center">
            <!-- Pin 圖標 -->
            <div class="pin-icon">
                <svg width="50" height="70" viewBox="0 0 50 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Shadow -->
                    <ellipse cx="25" cy="65" rx="10" ry="3" fill="rgba(0,0,0,0.2)"/>
                    <!-- Pin body -->
                    <path d="M25 0C14.5 0 6 8.5 6 19C6 32 25 55 25 55C25 55 44 32 44 19C44 8.5 35.5 0 25 0Z" 
                          fill="#3B82F6" stroke="white" stroke-width="2"/>
                    <!-- Inner circle -->
                    <circle cx="25" cy="19" r="8" fill="white"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- 地址顯示卡片 -->
    <div id="address-card" class="fixed top-4 left-4 right-4 bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-4 z-[999] transition-all duration-300">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 mt-1">
                <i class="material-icons text-blue-500 dark:text-blue-400">place</i>
            </div>
            <div class="flex-1 min-w-0">
                <p id="current-address" class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                    {{ __('Move map to select location') }}
                </p>
                <p id="current-coordinates" class="text-xs text-gray-500 dark:text-gray-400"></p>
            </div>
            <div id="loading-indicator" class="flex-shrink-0 hidden">
                <i class="material-icons text-gray-400 animate-spin">refresh</i>
            </div>
        </div>
    </div>
    
    <!-- 選擇按鈕 -->
    <div class="fixed bottom-28 w-full z-[999]">
        <div class="flex justify-center w-full px-4">
            <button id="select-button"
                class="w-full max-w-md bg-primary hover:bg-primary-accent dark:bg-primary-dark dark:hover:bg-primary text-gray-100 dark:text-gray-200 py-4 rounded-xl font-semibold text-md transition shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed">
                {{ __('Select') }}
            </button>
        </div>
    </div>
    
    <style>
        #center-marker.bouncing {
            animation: bounce 0.5s ease;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translate(-50%, -100%); }
            50% { transform: translate(-50%, -120%); }
        }
        
        .pin-icon {
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }
    </style>
</x-app-layout>

<script type="module">
    $(document).ready(function() {
        console.log('Map page loaded');
        
        const _apiKey = '{{ config('geocoding.api_key') }}';
        console.log('API Key loaded:', _apiKey ? 'Yes' : 'No');

        var _location = "";
        var _locationData = null; // 存储完整的地址数据
        var reverseGeocodeTimeout = null;
        var isReverseGeocoding = false;
        
        // 检查URL参数，看是否为group booking选择地址
        const urlParams = new URLSearchParams(window.location.search);
        const passengerIndex = urlParams.get('passenger');
        const returnUrl = urlParams.get('return');
        
        console.log('URL parameters:', { passengerIndex, returnUrl });

        // 檢查 Leaflet 是否加載
        if (typeof L === 'undefined') {
            console.error('Leaflet is not loaded');
            return;
        }

        // 初始化地圖，設置香港中心
        var map = L.map("map", {
            zoomControl: true,
            attributionControl: false
        }).setView([22.3193, 114.1694], 13);
        console.log('Map initialized');

        var tiles = L.esri.Vector.vectorBasemapLayer(localStorage.getItem('dark-mode') === 'true' ?
            "arcgis/streets-night" : "arcgis/streets", {
                token: _apiKey
            }).addTo(map);

        // 搜索控制
        var searchControl = L.esri.Geocoding.geosearch({
            useMapBounds: false,
            placeholder: '{{ __('Search for pickup location in Hong Kong...') }}',
            providers: [
                L.esri.Geocoding.arcgisOnlineProvider({
                    apikey: _apiKey,
                    params: {
                        location: '114.1694,22.3193',
                        distance: 50000
                    }
                })
            ]
        }).addTo(map);

        var results = L.layerGroup().addTo(map);

        // 反向地理編碼函數
        function reverseGeocode(lat, lng) {
            if (isReverseGeocoding) return;
            
            isReverseGeocoding = true;
            $('#loading-indicator').removeClass('hidden');
            $('#select-button').prop('disabled', true);
            
            console.log('Reverse geocoding:', lat, lng);
            
            // 使用 Esri 反向地理編碼 API
            const url = `https://geocode-api.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=json&location=${lng},${lat}&token=${_apiKey}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('Reverse geocode result:', data);
                    
                    if (data.address) {
                        const address = data.address;
                        let formattedAddress = '';
                        
                        // 構建地址字符串
                        if (address.Address) formattedAddress += address.Address;
                        if (address.Neighborhood && formattedAddress) formattedAddress += ', ' + address.Neighborhood;
                        else if (address.Neighborhood) formattedAddress += address.Neighborhood;
                        if (address.City && formattedAddress) formattedAddress += ', ' + address.City;
                        else if (address.City) formattedAddress += address.City;
                        
                        if (!formattedAddress) {
                            formattedAddress = `${address.LongLabel || address.ShortLabel || 'Selected Location'}`;
                        }
                        
                        _location = formattedAddress;
                        _locationData = {
                            formatted_address: formattedAddress,
                            lat: lat,
                            lng: lng,
                            place_name: formattedAddress,
                            geometry: {
                                coordinates: [lng, lat]
                            },
                            attributes: address
                        };
                        
                        $('#current-address').text(formattedAddress);
                        $('#current-coordinates').text(`${lat.toFixed(6)}, ${lng.toFixed(6)}`);
                        $('#select-button').prop('disabled', false);
                    } else {
                        $('#current-address').text('{{ __('Unable to get address') }}');
                        $('#current-coordinates').text(`${lat.toFixed(6)}, ${lng.toFixed(6)}`);
                        $('#select-button').prop('disabled', false);
                    }
                })
                .catch(error => {
                    console.error('Reverse geocode error:', error);
                    $('#current-address').text('{{ __('Unable to get address') }}');
                    $('#current-coordinates').text(`${lat.toFixed(6)}, ${lng.toFixed(6)}`);
                    $('#select-button').prop('disabled', false);
                })
                .finally(() => {
                    isReverseGeocoding = false;
                    $('#loading-indicator').addClass('hidden');
                });
        }
        
        // 地圖移動事件 - 使用防抖
        map.on('movestart', function() {
            $('#center-marker').addClass('bouncing');
        });
        
        map.on('moveend', function() {
            $('#center-marker').removeClass('bouncing');
            
            const center = map.getCenter();
            
            // 清除之前的timeout
            if (reverseGeocodeTimeout) {
                clearTimeout(reverseGeocodeTimeout);
            }
            
            // 延遲執行反向地理編碼（防抖 500ms）
            reverseGeocodeTimeout = setTimeout(() => {
                reverseGeocode(center.lat, center.lng);
            }, 500);
        });

        // 搜索結果處理
        searchControl.on("results", function(data) {
            console.log('Search results:', data);
            results.clearLayers();
            
            if (data.results && data.results.length > 0) {
                const result = data.results[0];
                
                // 移動地圖到搜索結果
                map.setView(result.latlng, 16);
                
                // 地圖移動後會自動觸發 moveend 事件來更新地址
            }
        });
        
        // 初始化：獲取當前中心點的地址
        const initialCenter = map.getCenter();
        reverseGeocode(initialCenter.lat, initialCenter.lng);
        
        // 定位到用戶當前位置
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const userLocation = [position.coords.latitude, position.coords.longitude];
                    map.setView(userLocation, 15);
                    console.log('User location:', userLocation);
                },
                function(error) {
                    console.log('Geolocation error:', error);
                }
            );
        }

        $('#select-button').click(function() {
            console.log('Select button clicked, location:', _location);
            $('#select-button').prop('disabled', true);
            
            if (passengerIndex !== null && returnUrl) {
                // 这是为 group booking 中的乘客选择地址
                console.log('Group booking passenger selection:', passengerIndex);
                
                // 构建返回URL，包含选择的地址和乘客索引
                const locationParam = encodeURIComponent(JSON.stringify(_locationData));
                const redirectUrl = `${returnUrl}?passenger=${passengerIndex}&location=${locationParam}`;
                
                console.log('Redirecting to:', redirectUrl);
                window.location.href = redirectUrl;
                
            } else {
                // 这是单人预订的地址选择，使用原有的session方式
                $.ajax({
                    url: "/set-session",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        location: _location,
                    },
                    success: function(response) {
                        console.log('Session updated successfully');
                        
                        // 触发location-selected事件，让show.blade.php能够接收到地址更新
                        if (window.opener) {
                            // 如果是在弹窗中打开的，向父窗口发送事件
                            window.opener.postMessage({
                                type: 'location-selected',
                                location: _locationData
                            }, '*');
                        } else {
                            // 如果是在同一个窗口中，触发事件
                            window.dispatchEvent(new CustomEvent('location-selected', {
                                detail: { location: _locationData }
                            }));
                        }
                        
                        // 獲取來源頁面，如果沒有則默認回到 dashboard
                        setTimeout(() => {
                            if (document.referrer && !document.referrer.includes('/map')) {
                                window.location.replace(document.referrer);
                            } else {
                                window.location.replace("{{ route('dashboard') }}");
                            }
                        }, 100);
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax error:', error);
                        $('#select-button').prop('disabled', false);
                    }
                });
            }
        });

    });
</script>
