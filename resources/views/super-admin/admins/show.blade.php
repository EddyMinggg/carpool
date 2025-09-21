@extends('super-admin.layout')

@section('title', 'Admin Details')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Admin Details - {{ $admin->username }}</h2>
        <div class="flex space-x-2">
            <a href="{{ route('super-admin.admins.edit', $admin->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('super-admin.admins.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">User Information</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Username</label>
                        <p class="text-gray-900 font-medium">{{ $admin->username }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $admin->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Role</label>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $admin->isSuperAdmin() ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $admin->getRoleName() }}
                        </span>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Account Status</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email Verified</label>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $admin->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $admin->email_verified_at ? 'Verified' : 'Not Verified' }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Created At</label>
                        <p class="text-gray-900">{{ $admin->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                        <p class="text-gray-900">{{ $admin->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>

            @if($admin->id !== Auth::user()->id)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Danger Zone</h3>
                    <form action="{{ route('super-admin.admins.destroy', $admin->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors" onclick="return confirm('Are you sure you want to delete this admin? This action cannot be undone.')">
                            <i class="fas fa-trash mr-2"></i>Delete Admin
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection