@section('Title', __('Map'))
<x-app-layout>
    <div id="map"></div>
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
        const _apiKey = '{{ config('geocoding.api_key') }}';

        var _location = "";

        var map = L.map("map").setView([22.3, 114.1], 10);
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
            results.clearLayers();
            $.each(data.results, function(index, result) {
                results.addLayer(L.marker(result.latlng));
            });

            $('#select-button').show();
            _location = data.text;
        });

        $('#select-button').click(function() {
            $.ajax({
                url: "/set-session",
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    location: _location,
                },
                success: function(response) {
                    $('#select-button').prop('disabled', true);
                    window.location.replace("{{ route('dashboard') }}");
                }
            });
        });

    });
</script>
