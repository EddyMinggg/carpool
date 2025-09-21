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
        /* 側邊欄樣式 */
        .sidebar {
            width: 240px !important;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 10;
            transition: transform 0.3s ease-in-out;
            transform: translateX(0);
        }
        
        /* 側邊欄收合狀態 */
        .sidebar.collapsed {
            transform: translateX(-240px);
        }

        /* 主內容區域 */
        .main-content {
            margin-left: 240px !important;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
            width: calc(100% - 240px) !important;
            max-width: none !important;
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        
        /* 側邊欄收合時主內容區域 */
        .main-content.expanded {
            margin-left: 0 !important;
            width: 100% !important;
        }
        
        /* 漢堡按鈕樣式 */
        .hamburger-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 20;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hamburger-btn:hover {
            background: #b91c1c;
            transform: scale(1.05);
        }
        
        .hamburger-btn.sidebar-open {
            left: 20px;
        }
        
        .hamburger-btn.sidebar-closed {
            left: 20px;
        }
        
        /* 漢堡圖標動畫 */
        .hamburger-icon {
            font-size: 18px;
            transition: transform 0.3s ease;
        }
        
        .hamburger-btn.sidebar-closed .hamburger-icon {
            transform: rotate(180deg);
        }
        
        /* 覆蓋層（移動設備用） */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 5;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* 響應式設計 */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-240px);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 80px 20px 20px 20px; /* 為漢堡按鈕留空間 */
            }
            
            .hamburger-btn {
                left: 20px;
            }
        }
        
        /* 側邊欄內容在移動設備上的優化 */
        @media (max-width: 768px) {
            .sidebar nav a {
                padding: 12px 16px;
                font-size: 16px;
            }
            
            .sidebar nav a i {
                margin-right: 12px;
                width: 20px;
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-0 m-0">
    <div class="flex">
        <!-- 漢堡按鈕 -->
        <button id="hamburger-btn" class="hamburger-btn sidebar-open">
            <i class="fas fa-bars hamburger-icon"></i>
        </button>
        
        <!-- 側邊欄覆蓋層（移動設備用） -->
        <div id="sidebar-overlay" class="sidebar-overlay"></div>
        
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-gray-800 text-white">
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
        <main id="main-content" class="main-content">
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
    
    <!-- 側邊欄控制 JavaScript -->
    <script>
    $(document).ready(function() {
        const hamburgerBtn = $('#hamburger-btn');
        const sidebar = $('#sidebar');
        const mainContent = $('#main-content');
        const sidebarOverlay = $('#sidebar-overlay');
        const hamburgerIcon = $('.hamburger-icon');
        
        let sidebarOpen = true;
        
        // 檢測是否為移動設備
        function isMobile() {
            return window.innerWidth <= 768;
        }
        
        // 初始化側邊欄狀態
        function initSidebar() {
            if (isMobile()) {
                // 移動設備默認隱藏側邊欄
                sidebarOpen = false;
                sidebar.removeClass('show');
                hamburgerBtn.removeClass('sidebar-open').addClass('sidebar-closed');
                hamburgerIcon.removeClass('fa-bars').addClass('fa-bars');
            } else {
                // 桌面設備默認顯示側邊欄
                sidebarOpen = true;
                sidebar.removeClass('collapsed');
                mainContent.removeClass('expanded');
                hamburgerBtn.removeClass('sidebar-closed').addClass('sidebar-open');
                hamburgerIcon.removeClass('fa-times').addClass('fa-bars');
            }
        }
        
        // 切換側邊欄
        function toggleSidebar() {
            if (isMobile()) {
                // 移動設備邏輯
                if (sidebarOpen) {
                    sidebar.removeClass('show');
                    sidebarOverlay.removeClass('active');
                    hamburgerIcon.removeClass('fa-times').addClass('fa-bars');
                } else {
                    sidebar.addClass('show');
                    sidebarOverlay.addClass('active');
                    hamburgerIcon.removeClass('fa-bars').addClass('fa-times');
                }
            } else {
                // 桌面設備邏輯
                if (sidebarOpen) {
                    sidebar.addClass('collapsed');
                    mainContent.addClass('expanded');
                    hamburgerBtn.removeClass('sidebar-open').addClass('sidebar-closed');
                    hamburgerIcon.removeClass('fa-bars').addClass('fa-chevron-right');
                } else {
                    sidebar.removeClass('collapsed');
                    mainContent.removeClass('expanded');
                    hamburgerBtn.removeClass('sidebar-closed').addClass('sidebar-open');
                    hamburgerIcon.removeClass('fa-chevron-right').addClass('fa-bars');
                }
            }
            
            sidebarOpen = !sidebarOpen;
            
            // 觸發 DataTables 重新計算（如果存在）
            setTimeout(function() {
                if ($.fn.DataTable) {
                    $('.dataTable').each(function() {
                        if ($.fn.DataTable.isDataTable(this)) {
                            $(this).DataTable().columns.adjust().responsive.recalc();
                        }
                    });
                }
            }, 350); // 等待動畫完成
        }
        
        // 漢堡按鈕點擊事件
        hamburgerBtn.on('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
        
        // 覆蓋層點擊事件（移動設備）
        sidebarOverlay.on('click', function() {
            if (isMobile() && sidebarOpen) {
                toggleSidebar();
            }
        });
        
        // 點擊側邊欄外部時關閉（移動設備）
        $(document).on('click', function(e) {
            if (isMobile() && sidebarOpen && 
                !sidebar.is(e.target) && 
                !sidebar.has(e.target).length && 
                !hamburgerBtn.is(e.target) && 
                !hamburgerBtn.has(e.target).length) {
                toggleSidebar();
            }
        });
        
        // 窗口大小變化事件
        $(window).on('resize', function() {
            const wasMobile = !sidebarOpen && $('.sidebar').hasClass('show');
            const nowMobile = isMobile();
            
            if (wasMobile !== nowMobile) {
                initSidebar();
            }
        });
        
        // 鍵盤快捷鍵 (Ctrl + B)
        $(document).on('keydown', function(e) {
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                toggleSidebar();
            }
        });
        
        // 初始化
        initSidebar();
        
        // 為側邊欄導航項添加觸控友好性
        $('.sidebar nav a').on('touchstart', function() {
            $(this).addClass('bg-gray-600');
        }).on('touchend touchcancel', function() {
            $(this).removeClass('bg-gray-600');
        });
    });
    </script>
    
    @stack('scripts')
</body>
</html>