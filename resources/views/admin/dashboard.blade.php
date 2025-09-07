@extends('admin.layout')

@section('content')
    <div class="container mx-auto px-4">
        <!-- 页面标题 -->
        <header class="py-6 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-500 mt-1">Overview of your carpool system performance</p>
        </header>

        <!-- 统计卡片区域 - 优化间距和响应式布局 -->
        <section class="py-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- 總用戶數卡片 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 font-medium">Total Users</h3>
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
                        </div>
                    </div>
                </div>
                <!-- 總行程數卡片 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 font-medium">Total Trips</h3>
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <i class="fa fa-car"></i>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-3xl font-bold text-gray-900">{{ $totalTrips }}</p>
                        </div>
                    </div>
                </div>
                <!-- 待處理行程卡片 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 font-medium">Pending Trips</h3>
                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-3xl font-bold text-gray-900">{{ $pendingTrips }}</p>
                        </div>
                    </div>
                </div>
                <!-- 優惠碼使用卡片 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 font-medium">Coupons Used</h3>
                            <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-600">
                                <i class="fa fa-ticket"></i>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-3xl font-bold text-gray-900">{{ $couponUsed }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 近期行程表格 - 优化宽度和间距 -->
        <section class="py-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900">Upcoming Trips</h2>
                        <a href="{{ route('admin.trips.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                            View all <i class="fa fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trip ID</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departure Time</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($upcomingTrips as $trip)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->trip_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $trip->start_place }} → {{ $trip->end_place }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $trip->plan_departure_time->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" 
                                                 style="width: {{ ($trip->current_people / $trip->max_people) * 100 }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500 mt-1 block">
                                            {{ $trip->current_people }}/{{ $trip->max_people }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Upcoming
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                        <i class="fa fa-calendar-o text-2xl mb-2 text-gray-300"></i>
                                        <p>No upcoming trips scheduled</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
