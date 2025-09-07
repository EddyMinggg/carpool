@extends('admin.layout')

@section('title', 'Coupon Management - Details')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Coupon Details #{{ $coupon->id }}</h2>
        <a href="{{ route('admin.coupons.index') }}" class="text-blue-600 hover:text-blue-900 text-sm">
            ← Back to Coupon List
        </a>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Basic Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 py-2">
            <div class="py-3">
                <p class="text-sm text-gray-500">Code</p>
                <p class="text-gray-900">{{ $coupon->code }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Discount Amount</p>
                <p class="text-gray-900">¥{{ number_format($coupon->discount_amount, 2) }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Valid From</p>
                <p class="text-gray-900">{{ $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : '-' }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Valid To</p>
                <p class="text-gray-900">{{ $coupon->valid_to ? $coupon->valid_to->format('Y-m-d') : '-' }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Enabled</p>
                <p class="text-gray-900">{{ $coupon->enabled ? 'Yes' : 'No' }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Usage Limit</p>
                <p class="text-gray-900">{{ $coupon->usage_limit ?? '-' }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Used Count</p>
                <p class="text-gray-900">{{ $coupon->used_count }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Created At</p>
                <p class="text-gray-900">{{ $coupon->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Actions</h3>
        <div class="flex space-x-4">
            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this coupon?')">Delete</button>
            </form>
        </div>
    </div>
@endsection
