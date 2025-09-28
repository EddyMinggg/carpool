<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Carpool Management System - Admin Panel')</title>
    
    <!-- jQuery 必須在其他所有 JavaScript 之前加載 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- 保存原始 jQuery 引用以供 DataTables 使用 -->
    <script>
        window.jQueryOriginal = window.jQuery.noConflict(true);
        window.$Original = window.jQueryOriginal;
    </script>
    
    @vite(['resources/css/app.css'])
    
    <!-- 恢復 DataTables 所需的 jQuery -->
    <script>
        window.jQuery = window.jQueryOriginal;
        window.$ = window.jQueryOriginal;
    </script>
    
    <!-- Alpine.js 獨立加載 (用於 modal 和其他互動功能) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    
    <!-- DataTables 樣式修復 -->
    <style>
        /* 修復 DataTables 下拉框樣式衝突 */
        .dataTables_length select {
            padding: 0.5rem 2rem 0.5rem 0.75rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            background-color: white !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.5rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.2em 1.2em !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            color: #374151 !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            min-width: 70px !important;
            max-width: 80px !important;
            width: 80px !important;
            height: auto !important;
        }
        
        .dataTables_length select:focus {
            outline: 2px solid #3b82f6 !important;
            outline-offset: 2px !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1) !important;
        }
        
        /* 修復搜索框樣式 */
        .dataTables_filter input {
            padding: 0.5rem 0.75rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            background-color: white !important;
            color: #374151 !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            margin-left: 0.5rem !important;
            width: 200px !important;
        }
        
        .dataTables_filter input:focus {
            outline: 2px solid #3b82f6 !important;
            outline-offset: 2px !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1) !important;
        }
        
        /* 修復分頁按鈕樣式 */
        .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.75rem !important;
            margin: 0 0.125rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            background-color: white !important;
            color: #374151 !important;
            text-decoration: none !important;
            font-size: 0.875rem !important;
            transition: all 0.2s ease !important;
            display: inline-block !important;
        }
        
        .dataTables_paginate .paginate_button:hover {
            background-color: #f3f4f6 !important;
            border-color: #9ca3af !important;
            color: #374151 !important;
        }
        
        .dataTables_paginate .paginate_button.current {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
        }
        
        .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
        }
        
        .dataTables_paginate .paginate_button.disabled:hover {
            background-color: white !important;
            border-color: #d1d5db !important;
            color: #9ca3af !important;
        }
        
        /* 修復 DataTables 按鈕樣式 */
        .dt-button {
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            background-color: white !important;
            color: #374151 !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            text-decoration: none !important;
        }
        
        .dt-button:hover {
            background-color: #f3f4f6 !important;
            border-color: #9ca3af !important;
        }
        
        /* 確保 DataTables 元素不會被 Tailwind reset 影響 */
        .dataTables_wrapper select,
        .dataTables_wrapper input {
            box-sizing: border-box !important;
        }
        
        /* 修復響應式表格樣式 */
        .dtr-details {
            background-color: #f9fafb !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.375rem !important;
            padding: 1rem !important;
        }
    </style>
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
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        
        /* 側邊欄收合時主內容區域 */
        .main-content.expanded {
            margin-left: 0 !important;
        }
        
        /* 頂部導航欄 */
        .top-nav {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 0;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 20;
        }
        
        /* 漢堡按鈕 - 內置在頂部導航欄 */
        .hamburger-btn {
            background: none;
            border: none;
            padding: 12px;
            cursor: pointer;
            color: #374151;
            transition: all 0.2s ease;
            border-radius: 0.375rem;
            margin-left: 16px;
        }
        
        .hamburger-btn:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }
        
        .hamburger-btn:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
        
        /* 頁面標題區域 */
        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-left: 16px;
        }
        
        /* 用戶信息區域 */
        .user-info {
            display: flex;
            align-items: center;
            margin-right: 16px;
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        /* 頁面內容區域 */
        .page-content {
            padding: 24px;
        }
        
        /* 響應式設計 */
        @media (max-width: 768px) {
            /* 移動版強制防止水平滾動 */
            html, body {
                overflow-x: hidden !important;
                width: 100vw !important;
                max-width: 100vw !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            /* 移動版佈局重置 */
            .flex {
                overflow-x: hidden !important;
                width: 100vw !important;
                max-width: 100vw !important;
            }
            
            .sidebar {
                transform: translateX(-240px);
                position: fixed !important;
                z-index: 1000 !important;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            
            /* 移動版頂部導航 */
            .top-nav {
                width: 100vw !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            
            /* 移動版頁面內容 */
            .page-content {
                padding: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            
            .page-title {
                font-size: 1.125rem;
            }
            
            .user-info {
                display: none;
            }
        }
        
        /* 側邊欄內容在移動設備上的優化 */
        @media (max-width: 768px) {
            .sidebar nav a {
                padding: 12px 10px;
            }
            
            .sidebar nav a i {
                margin-right: 5px;
                width: 20px;
            }
        }
    </style>
    
    @stack('head-styles')
</head>
<body class="bg-gray-100" style="padding: 0 !important; margin: 0 !important; overflow-x: hidden !important;">
    <div class="flex" style="overflow-x: hidden !important; width: 100vw !important; max-width: 100vw !important;">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-gray-800 text-white">
            <div class="p-4 border-b border-gray-700">
                <h1 class="text-xl font-bold">Carpool Management</h1>
            </div>
            <nav class="p-4">
                <a href="{{ route('admin.dashboard') }}" class="text-sm block py-2 px-3 rounded mb-2 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700 text-gray-200' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="text-sm block py-2 px-3 rounded mb-2 {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700 text-gray-200' }}">
                    <i class="fas fa-users mr-3"></i>Users
                </a>
                <a href="{{ route('admin.drivers.index') }}" class="text-sm block py-2 px-3 rounded mb-2 {{ request()->routeIs('admin.drivers.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700 text-gray-200' }}">
                    <i class="fa fa-drivers-license mr-3"></i>Drivers
                </a>
                <a href="{{ route('admin.trips.index') }}" class="text-sm block py-2 px-3 rounded mb-2 {{ request()->routeIs('admin.trips.*') && !request()->routeIs('admin.payment-confirmation.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700 text-gray-200' }}">
                    <i class="fas fa-route mr-3"></i>Trips
                </a>
                <a href="#" onclick="showPaymentConfirmationMenu()" class="text-sm block py-2 px-3 rounded mb-2 {{ request()->routeIs('admin.payment-confirmation.*') ? 'bg-green-600 text-white' : 'hover:bg-gray-700 text-gray-200' }}">
                    <i class="fas fa-credit-card mr-3"></i>Payment Confirmation
                </a>
                <a href="{{ route('admin.orders.index') }}" class="text-sm block py-2 px-3 rounded mb-2 {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700 text-gray-200' }}">
                    <i class="fas fa-shopping-cart mr-3"></i>Orders
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="text-sm block py-2 px-3 rounded mb-2 {{ request()->routeIs('admin.coupons.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700 text-gray-200' }}">
                    <i class="fas fa-ticket-alt mr-3"></i>Coupons
                </a>
                
                @if(Auth::user()->isSuperAdmin())
                    <div class="border-t border-gray-700 mt-6 pt-4">
                        <div class="mb-2">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3">Super Admin</span>
                        </div>
                        <a href="{{ route('super-admin.admins.index') }}" class="text-sm block py-2 px-3 rounded mb-2 {{ request()->routeIs('super-admin.admins.*') ? 'bg-red-600 text-white' : '' }}">
                            <i class="fas fa-users-cog mr-3"></i>Manage Admins
                        </a>
                    </div>
                @endif
                
                <div class="border-t border-gray-700 mt-6">
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="text-sm w-full text-left py-2 px-3 rounded hover:bg-red-600 text-gray-200">
                            <i class="fas fa-sign-out-alt mr-3"></i>Logout
                        </button>
                    </form>
                </div>

            </nav>
        </aside>

        <!-- Main Content -->
        <main id="main-content" class="main-content w-full">
            <!-- Top Navigation Bar -->
            <header class="top-nav">
                <div class="flex items-center">
                    <!-- Hamburger Button -->
                    <button id="hamburger-btn" class="hamburger-btn">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    
                    <!-- Page Title -->
                    <h1 class="page-title">
                        @yield('page-title', 'Dashboard')
                    </h1>
                </div>
                
                <!-- User Info -->
                <div class="user-info">
                    <span class="mr-2">Welcome, {{ Auth::user()->username }}</span>
                    <i class="fas fa-user-circle text-lg"></i>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
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
            </div>
        </main>
    </div>

    <!-- DataTables 和相關依賴 -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    
    <!-- 全局 DataTables 初始化檢查 -->
    <script>
        window.dataTablesReady = false;
        
        // 確保使用正確的 jQuery 實例初始化 DataTables
        (function($) {
            $(document).ready(function() {
                console.log('=== DataTables Loading Check ===');
                console.log('jQuery version:', $.fn.jquery);
                console.log('DataTables available:', typeof $.fn.DataTable !== 'undefined');
                
                if (typeof $.fn.DataTable !== 'undefined') {
                    console.log('DataTables version:', $.fn.dataTable.version);
                    window.dataTablesReady = true;
                    
                    // 觸發自定義事件通知 DataTables 已準備好
                    $(document).trigger('dataTablesReady');
                } else {
                    console.error('DataTables failed to load');
                    
                    // 如果 DataTables 未加載，嘗試延遲初始化
                    setTimeout(function() {
                        if (typeof $.fn.DataTable !== 'undefined') {
                            console.log('DataTables loaded after delay');
                            window.dataTablesReady = true;
                            $(document).trigger('dataTablesReady');
                        }
                    }, 1000);
                }
            });
        })(window.jQueryOriginal || window.jQuery);
    </script>
    
    <!-- 側邊欄控制 JavaScript -->
    <script>
    // 使用正確的 jQuery 實例
    (function($) {
        $(document).ready(function() {
        const hamburgerBtn = $('#hamburger-btn');
        const sidebar = $('#sidebar');
        const mainContent = $('#main-content');
        
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
            } else {
                // 桌面設備默認顯示側邊欄
                sidebarOpen = true;
                sidebar.removeClass('collapsed');
                mainContent.removeClass('expanded');
            }
        }
        
        // 切換側邊欄
        function toggleSidebar() {
            if (isMobile()) {
                // 移動設備邏輯
                if (sidebarOpen) {
                    sidebar.removeClass('show');
                } else {
                    sidebar.addClass('show');
                }
            } else {
                // 桌面設備邏輯
                if (sidebarOpen) {
                    sidebar.addClass('collapsed');
                    mainContent.addClass('expanded');
                } else {
                    sidebar.removeClass('collapsed');
                    mainContent.removeClass('expanded');
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
            initSidebar();
        });
        
        // 初始化
        initSidebar();
        });
    })(window.jQueryOriginal || window.jQuery);
    </script>
    
    <!-- Payment Confirmation Menu Script -->
    <script>
    function showPaymentConfirmationMenu() {
        // 創建一個簡單的下拉菜單來選擇要查看的行程
        const modal = document.createElement('div');
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        `;
        
        modal.innerHTML = `
            <div style="background: white; border-radius: 12px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);">
                <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0;">Select Trip for Payment Confirmation</h3>
                </div>
                <div id="trip-list" style="padding: 20px;">
                    <div style="text-align: center; color: #6b7280;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 8px;"></i>
                        <p>Loading trips...</p>
                    </div>
                </div>
                <div style="padding: 20px; border-top: 1px solid #e5e7eb; text-align: right;">
                    <button onclick="closePaymentConfirmationMenu()" style="background: #6b7280; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">
                        Cancel
                    </button>
                </div>
            </div>
        `;
        
        modal.onclick = function(e) {
            if (e.target === modal) {
                closePaymentConfirmationMenu();
            }
        };
        
        document.body.appendChild(modal);
        window.paymentConfirmationModal = modal;
        
        // 獲取行程列表
        fetch('/admin/trips', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const tripList = document.getElementById('trip-list');
            if (data && data.length > 0) {
                tripList.innerHTML = data.slice(0, 10).map(trip => `
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s;" 
                         onmouseover="this.style.backgroundColor='#f3f4f6'" 
                         onmouseout="this.style.backgroundColor='white'"
                         onclick="window.location.href='/admin/payment-confirmation/trip/${trip.id}'">
                        <div style="font-weight: 600; color: #111827;">#${trip.id} - ${trip.dropoff_location}</div>
                        <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                            ${trip.planned_departure_time} | ${trip.joins_count || 0} participants
                        </div>
                    </div>
                `).join('');
            } else {
                tripList.innerHTML = `
                    <div style="text-align: center; color: #6b7280;">
                        <i class="fas fa-inbox" style="font-size: 24px; margin-bottom: 8px;"></i>
                        <p>No trips found</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading trips:', error);
            const tripList = document.getElementById('trip-list');
            tripList.innerHTML = `
                <div style="text-align: center; color: #ef4444;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 8px;"></i>
                    <p>Failed to load trips. Please try again.</p>
                    <button onclick="window.location.href='/admin/trips'" style="background: #3b82f6; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; margin-top: 8px;">
                        Go to Trip Management
                    </button>
                </div>
            `;
        });
    }
    
    function closePaymentConfirmationMenu() {
        if (window.paymentConfirmationModal) {
            document.body.removeChild(window.paymentConfirmationModal);
            window.paymentConfirmationModal = null;
        }
    }
    </script>
    
    @stack('scripts')
</body>
</html>