@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-sm text-gray-500">Total Users</p>
        <p class="text-2xl font-bold text-blue-700">{{ $userCount }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-sm text-gray-500">Total Trips</p>
        <p class="text-2xl font-bold text-green-700">{{ $tripCount }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-sm text-gray-500">Total Coupons</p>
        <p class="text-2xl font-bold text-yellow-700">{{ $couponCount }}</p>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-sm text-gray-500">Total Income</p>
        <p class="text-2xl font-bold text-amber-700">¥{{ number_format($income, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-sm text-gray-500">Active Users (30 days)</p>
        <p class="text-2xl font-bold text-indigo-700">{{ $activeUsers }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-sm text-gray-500">Coupons Used</p>
        <p class="text-2xl font-bold text-pink-700">{{ $couponUsed }}</p>
    </div>
</div>
@endsection
