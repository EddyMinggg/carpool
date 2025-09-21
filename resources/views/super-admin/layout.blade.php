<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Super Admin') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- CSS -->
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    
    <style>
        .sidebar {
            width: 240px !important;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 10;
        }

        .main-content {
            margin-left: 240px !important;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
            width: calc(100% - 240px) !important;
            max-width: none !important;
        }
    </style>
</head>
<body class="bg-gray-100 p-0 m-0">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="sidebar bg-gray-800 text-white">
            <div class="p-4 border-b border-gray-700">
                <h1 class="text-xl font-bold">🚀 Super Admin</h1>
                <p class="text-gray-300 text-sm">{{ Auth::user()->username }}</p>
            </div>
            
            <nav class="p-4">
                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Admin Management</h3>
                    <a href="{{ route('super-admin.admins.index') }}" class="block py-2 px-3 rounded mb-2 {{ request()->routeIs('super-admin.admins.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700 text-gray-200' }}">
                        <i class="fas fa-users-cog mr-2"></i> Manage Admins
                    </a>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Quick Access</h3>
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 px-3 rounded mb-2 hover:bg-gray-700 text-gray-200">
                        <i class="fas fa-tachometer-alt mr-2"></i> Admin Panel
                    </a>
                    <a href="{{ route('dashboard') }}" class="block py-2 px-3 rounded mb-2 hover:bg-gray-700 text-gray-200">
                        <i class="fas fa-home mr-2"></i> User Dashboard
                    </a>
                </div>
                
                <form action="{{ route('logout') }}" method="POST" class="mt-6">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 px-3 rounded hover:bg-gray-700 text-gray-200">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </nav>
        </aside>
        
        <!-- Main Content -->
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
    
    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    @stack('scripts')
</body>
</html>