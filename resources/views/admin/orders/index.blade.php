@extends('admin.layout')
@section('title', 'Order Management')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Order List</h2>
</div>
<div class="bg-white rounded-lg shadow-md p-6">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Trip</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pickup Location</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fee (¥)</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Created At</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($orders as $order)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 text-sm text-gray-900">{{ $order->id }}</td>
                <td class="px-4 py-2 text-sm text-gray-900">{{ $order->user->username ?? 'Deleted User' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900">{{ $order->trip->pickup_location ?? '-' }} → {{ $order->trip->dropoff_location ?? '-' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900">{{ ucfirst($order->join_role) }}</td>
                <td class="px-4 py-2 text-sm text-gray-900">{{ $order->pickup_location ?? '-' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900">{{ number_format($order->user_fee, 2) }}</td>
                <td class="px-4 py-2 text-sm text-gray-900">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                <td class="px-4 py-2 text-sm">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
