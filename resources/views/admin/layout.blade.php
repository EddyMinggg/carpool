<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Carpool Management System - Admin Panel')</title>
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    <style>
        .sidebar {
            width: 240px !important;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 10;
        }

        /* Key update: Add w-full and remove any max-width */
        .main-content {
            margin-left: 240px !important;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
            width: calc(100% - 240px) !important; /* Critical: Fill remaining width */
            max-width: none !important; /* Ensure no max-width limits it */
        }
    </style>
</head>
<body class="bg-gray-100 p-0 m-0">
    <div class="flex">
        <!-- Sidebar: Use the fixed width class -->
        <aside class="sidebar bg-gray-800 text-white">
            <div class="p-4 border-b border-gray-700">
                <h1 class="text-xl font-bold">Carpool Management</h1>
            </div>
            <nav class="p-4">
                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-3 rounded mb-2 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.trips.index') }}" class="block py-2 px-3 rounded mb-2 {{ request()->routeIs('admin.trips.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                    Manage Trips
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-6">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 px-3 rounded hover:bg-gray-700">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content: Use the margin-matched class -->
        <main class="main-content">
            <!-- Top Notifications -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>
</body>
</html>