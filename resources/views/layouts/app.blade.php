<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') . ' | ' }} @yield('Title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Flowbite -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- ======================================= Map ======================================= -->
    <!-- Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@3.0.19/dist/esri-leaflet.js"></script>

    <!-- Load Esri Leaflet Geocoder from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.6/dist/esri-leaflet-geocoder.css"
        crossorigin="" />
    <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.6/dist/esri-leaflet-geocoder.js" crossorigin=""></script>

    <!-- Load Esri Leaflet Vector from CDN -->
    <script src="https://unpkg.com/esri-leaflet-vector@4.3.1/dist/esri-leaflet-vector.js" crossorigin=""></script>

    <style>
        #map {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }
        
        /* 移動端地址顯示優化 */
        @media (max-width: 640px) {
            .location-display {
                max-width: 100%;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            
            .location-display span {
                display: block;
                max-width: 100%;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            
            /* 確保容器不被子元素撐開 */
            .location-container {
                min-width: 0;
                flex: 1;
            }
        }
        
        /* Navigation 響應式優化 */
        @media (max-width: 375px) {
            /* 超小屏幕優化 (iPhone SE 等) */
            #header-location-picker {
                font-size: 0.7rem;
                padding: 0.375rem 0.5rem;
            }
            
            #header-location-picker i {
                font-size: 0.875rem;
                margin-right: 0.25rem;
            }
            
            /* 語言下拉菜單優化 */
            .dropdown-content {
                min-width: 10rem !important;
                width: max-content !important;
            }
        }
        
        @media (min-width: 376px) and (max-width: 414px) {
            /* 中型手機優化 (iPhone 12/13 等) */
            #header-location-picker {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
            }
            
            .dropdown-content {
                min-width: 11rem !important;
                width: max-content !important;
            }
        }
        
        @media (min-width: 415px) and (max-width: 640px) {
            /* 大型手機優化 (iPhone Pro Max 等) */
            #header-location-picker {
                font-size: 0.875rem;
                padding: 0.5rem 0.875rem;
            }
            
            .dropdown-content {
                min-width: 12rem !important;
                width: max-content !important;
            }
        }
        
        /* 語言下拉菜單通用優化 */
        .language-dropdown {
            min-width: fit-content;
        }
        
        .language-dropdown a {
            white-space: nowrap;
            padding-left: 1rem;
            padding-right: 1rem;
        }
    </style>

    <!-- Scripts - Dark Mode -->
    <script>
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.querySelector('html').classList.add('dark');
        } else {
            document.querySelector('html').classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen pb-20 bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        @if (!session('guest_mode'))
        @include('layouts.bottom-navigation')
        @endif
    </div>
</body>

</html>
