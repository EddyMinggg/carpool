@extends('admin.layout')
@section('title', 'Order Details')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Order Details #{{ $order->id }}</h2>
    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-900 text-sm">← Back to Order List</a>
</div>
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Basic Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 py-2">
        <div class="py-3">
            <p class="text-sm text-gray-500">User</p>
            <p class="text-gray-900">{{ $order->user->username ?? 'Deleted User' }}</p>
        </div>
        <div class="py-3">
            <p class="text-sm text-gray-500">Trip</p>
            <p class="text-gray-900">{{ $order->trip->start_place ?? '-' }} → {{ $order->trip->end_place ?? '-' }}</p>
        </div>
        <div class="py-3">
            <p class="text-sm text-gray-500">Role</p>
            <p class="text-gray-900">{{ ucfirst($order->join_role) }}</p>
        </div>
        <div class="py-3">
            <p class="text-sm text-gray-500">Pickup Location</p>
            <p class="text-gray-900">{{ $order->pickup_location ?? '-' }}</p>
        </div>
        <div class="py-3">
            <p class="text-sm text-gray-500">Fee (¥)</p>
            <p class="text-gray-900">{{ number_format($order->user_fee, 2) }}</p>
        </div>
        <div class="py-3">
            <p class="text-sm text-gray-500">Created At</p>
            <p class="text-gray-900">{{ $order->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>
</div>
@endsection
