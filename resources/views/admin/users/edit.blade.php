@extends('admin.layout')

@section('title', 'User Management - Edit')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit User #{{ $user->id }}</h2>
    <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" id="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('username', $user->username) }}">
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('email', $user->email) }}">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" id="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                @if(Auth::user()->is_admin === 2)
                    <!-- Super Admin 可以設定任何角色 -->
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="is_admin" id="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="0" {{ old('is_admin', $user->is_admin) == 0 ? 'selected' : '' }}>User</option>
                        <option value="1" {{ old('is_admin', $user->is_admin) == 1 ? 'selected' : '' }}>Admin</option>
                        <option value="2" {{ old('is_admin', $user->is_admin) == 2 ? 'selected' : '' }}>Super Admin</option>
                    </select>
                @else
                    <!-- 普通 Admin 只能設定 User/Admin (不能設定 Super Admin) -->
                    <label class="flex items-center">
                        <input type="checkbox" name="is_admin" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ old('is_admin', $user->is_admin) == 1 ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Admin</span>
                    </label>
                    @if($user->is_admin === 2)
                        <p class="mt-2 text-sm text-gray-500">This user is a Super Admin and cannot be modified by regular admins.</p>
                        <input type="hidden" name="is_admin" value="{{ $user->is_admin }}">
                    @endif
                @endif
            </div>
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Update User</button>
                <a href="{{ route('admin.users.show', $user->id) }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
@endsection
