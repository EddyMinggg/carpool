@extends('admin.layout')

@section('title', 'Coupon Management - Create')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Coupon</h2>
    <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code <span class="text-red-500">*</span></label>
                <input type="text" name="code" id="code" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('code') }}">
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-1">Discount Amount (¥) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="discount_amount" id="discount_amount" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('discount_amount') }}">
                @error('discount_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-1">Valid From</label>
                <input type="date" name="valid_from" id="valid_from" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('valid_from') }}">
                @error('valid_from')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="valid_to" class="block text-sm font-medium text-gray-700 mb-1">Valid To</label>
                <input type="date" name="valid_to" id="valid_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('valid_to') }}">
                @error('valid_to')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="enabled" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ old('enabled', true) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Enabled</span>
                </label>
            </div>
            <div class="mb-4">
                <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-1">Usage Limit</label>
                <input type="number" name="usage_limit" id="usage_limit" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('usage_limit') }}">
                @error('usage_limit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Create Coupon</button>
                <a href="{{ route('admin.coupons.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
@endsection
