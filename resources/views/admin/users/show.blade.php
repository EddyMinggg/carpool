@extends('admin.layout')

@section('title', 'User Management - Details')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">User Details #{{ $user->id }}</h2>
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-900 text-sm">
            ← Back to User List
        </a>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 py-2">
            <div class="py-3">
                <p class="text-sm text-gray-500">Username</p>
                <p class="text-gray-900">{{ $user->username }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Email</p>
                <p class="text-gray-900">{{ $user->email }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Phone</p>
                <p class="text-gray-900">{{ $user->phone }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Role</p>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                    {{ $user->is_admin === 2 ? 'bg-red-100 text-red-800' : ($user->is_admin === 1 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ $user->is_admin === 2 ? 'Super Admin' : ($user->is_admin === 1 ? 'Admin' : 'User') }}
                </span>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Registered At</p>
                <p class="text-gray-900">{{ $user->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Actions</h3>
        <div class="flex space-x-4">
            @if(!(Auth::user()->is_admin === 1 && $user->is_admin === 2))
                <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
            @endif
            
            @if(Auth::user()->id !== $user->id && !(Auth::user()->is_admin === 1 && $user->is_admin === 2))
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                </form>
            @endif
            
            @if(Auth::user()->is_admin === 1 && $user->is_admin === 2)
                <p class="text-gray-500 italic">Super Admin can only be managed by another Super Admin</p>
            @endif
        </div>
    </div>
@endsection
