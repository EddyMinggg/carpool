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
            });

            $('#select-button').show();
            _location = data.text;
        });

        $('#select-button').click(function() {
            console.log('Select button clicked, location:', _location);
            $.ajax({
                url: "/set-session",
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    location: _location,
                },
                success: function(response) {
                    console.log('Session updated successfully');
                    $('#select-button').prop('disabled', true);
                    
                    // 獲取來源頁面，如果沒有則默認回到 dashboard
                    if (document.referrer && !document.referrer.includes('/map')) {
                        window.location.replace(document.referrer);
                    } else {
                        window.location.replace("{{ route('dashboard') }}");
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ajax error:', error);
                }
            });
        });

    });
</script>
