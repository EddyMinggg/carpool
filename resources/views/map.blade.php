@section('Title', __('Map'))
<x-app-layout>
    <div id="map" style="height: 100vh; width: 100%;"></div>
    <div class="fixed bottom-24 w-full">
        <div class="flex justify-center w-full">
            <button id="select-button"
                class="w-1/2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white py-3 rounded-xl font-semibold text-md transition shadow-md hidden">
                {{ __('Select') }}
            </button>
        </div>
    </div>
</x-app-layout>

<script type="module">
    $(document).ready(function() {
        console.log('Map page loaded');
        
        const _apiKey = '{{ config('geocoding.api_key') }}';
        console.log('API Key loaded:', _apiKey ? 'Yes' : 'No');

        var _location = "";
        var _locationData = null; // 存储完整的地址数据
        
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

        var map = L.map("map").setView([22.3, 114.1], 10);
        console.log('Map initialized');

        var tiles = L.esri.Vector.vectorBasemapLayer(localStorage.getItem('dark-mode') === 'true' ?
            "arcgis/streets-night" : "arcgis/streets", {
                token: _apiKey
            }).addTo(map);

        var searchControl = L.esri.Geocoding.geosearch({
            useMapBounds: false,
            providers: [
                L.esri.Geocoding.arcgisOnlineProvider({
                    apikey: _apiKey
                })
            ]
        }).addTo(map);

        var results = L.layerGroup().addTo(map);

        searchControl.on("results", function(data) {
            console.log('Search results:', data);
            results.clearLayers();
            $.each(data.results, function(index, result) {
                results.addLayer(L.marker(result.latlng));
                // 存储第一个结果的完整数据
                if (index === 0) {
                    _locationData = {
                        formatted_address: data.text || result.text ,
                        lat: result.latlng.lat,
                        lng: result.latlng.lng,
                        place_name: data.text || result.text,
                        geometry: {
                            coordinates: [result.latlng.lng, result.latlng.lat]
                        }
                    };
                }
            });

            $('#select-button').show();
            _location = data.text;
        });

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
