@extends('admin.layout')

@section('title', 'Trip Management - Details')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Trip Details #{{ $trip->id }}</h2>
        <a href="{{ route('admin.trips.index') }}" class="text-blue-600 hover:text-blue-900 text-sm">
            ← Back to Trip List
        </a>
    </div>

    <!-- Trip Basic Information -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 py-2">
            <div class="py-3">
                <p class="text-sm text-gray-500">Creator</p>
                <p class="text-gray-900">{{ $trip->creator->username ?? 'Unknown User' }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Route</p>
                <p class="text-gray-900">{{ $trip->pickup_location }} → {{ $trip->dropoff_location }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Planned Departure</p>
                <p class="text-gray-900">{{ $trip->planned_departure_time->format('Y-m-d H:i') }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Max Capacity</p>
                <p class="text-gray-900">{{ $trip->max_people }} people</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Base Price</p>
                <p class="text-gray-900">¥{{ number_format($trip->base_price, 2) }}</p>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Status</p>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                    {{ $trip->trip_status === 'pending' ? 'bg-blue-100 text-blue-800' : 
                       ($trip->trip_status === 'voting' ? 'bg-yellow-100 text-yellow-800' : 
                       ($trip->trip_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                    {{ ucfirst($trip->trip_status) }}
                </span>
            </div>
            <div class="py-3">
                <p class="text-sm text-gray-500">Created At</p>
                <p class="text-gray-900">{{ $trip->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Participants List -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
            Participants ({{ optional($trip->joins)->count() ?? 0 }}/{{ $trip->max_people }})
        </h3>
        @if(empty($trip->joins) || $trip->joins->isEmpty())
            <p class="text-gray-500">No participants yet</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pickup Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voted?</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee (¥)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($trip->joins as $join)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $join->user->username ?? $join->user->name ?? 'Deleted User' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ucfirst($join->join_role) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $join->pickup_location ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($join->hasVoted())
                                        <span class="text-green-600">Yes</span>
                                    @else
                                        <span class="text-red-600">No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($join->user_fee, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Payment Records -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Payment Records</h3>
        @if(empty($trip->payments) || $trip->payments->isEmpty())
            <p class="text-gray-500">No payment records yet</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (¥)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($trip->payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->user->name ?? 'Deleted User' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($payment->payment_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $payment->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($payment->payment_status === 'refunded' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->payment_time ? $payment->payment_time->format('Y-m-d H:i') : 'Not Paid' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection