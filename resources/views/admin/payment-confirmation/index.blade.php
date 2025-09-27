@extends('admin.layout')

@section('title', 'Payment Confirmation - ' . $trip->dropoff_location)
@section('page-title', 'Payment Confirmation')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Payment Confirmation</h2>
            <p class="text-gray-600">Trip: {{ $trip->dropoff_location }}</p>
        </div>
        <a href="{{ route('admin.trips.show', $trip) }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            Back to Trip
        </a>
    </div>

    <div>
            <!-- Trip Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Trip Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Trip ID</label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $trip->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Destination</label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $trip->dropoff_location }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Departure</label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $trip->planned_departure_time->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-amber-100 dark:bg-amber-900/30 border-2 border-amber-300 dark:border-amber-600 p-4 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-amber-900 dark:text-amber-100">{{ $pendingPayments->count() }}</div>
                            <div class="text-sm font-medium text-amber-700 dark:text-amber-300">Pending Payments</div>
                        </div>
                        <div class="bg-emerald-100 dark:bg-emerald-900/30 border-2 border-emerald-300 dark:border-emerald-600 p-4 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-emerald-900 dark:text-emerald-100">{{ $confirmedPayments->count() }}</div>
                            <div class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Confirmed Payments</div>
                        </div>
                        <div class="bg-sky-100 dark:bg-sky-900/30 border-2 border-sky-300 dark:border-sky-600 p-4 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-sky-900 dark:text-sky-100">{{ $tripStats['total_members'] }}</div>
                            <div class="text-sm font-medium text-sky-700 dark:text-sky-300">Total Members</div>
                        </div>
                        <div class="bg-violet-100 dark:bg-violet-900/30 border-2 border-violet-300 dark:border-violet-600 p-4 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-violet-900 dark:text-violet-100">HK$ {{ number_format($tripStats['total_confirmed_amount'], 2) }}</div>
                            <div class="text-sm font-medium text-violet-700 dark:text-violet-300">Total Confirmed</div>
                        </div>
                    </div>
                    
                    <!-- Payment Type Breakdown -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="bg-orange-100 dark:bg-orange-900/40 border-2 border-orange-300 dark:border-orange-500 p-4 rounded-lg shadow-sm">
                            <div class="text-lg font-bold text-orange-900 dark:text-orange-100 mb-3">💰 Deposits (20%)</div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-600 rounded-lg p-2">
                                    <div class="text-xl font-bold text-emerald-800 dark:text-emerald-100">{{ $tripStats['confirmed_deposits'] }}</div>
                                    <div class="text-xs font-medium text-emerald-700 dark:text-emerald-300">Confirmed</div>
                                </div>
                                <div class="text-center bg-amber-50 dark:bg-amber-900/50 border border-amber-200 dark:border-amber-600 rounded-lg p-2">
                                    <div class="text-xl font-bold text-amber-800 dark:text-amber-100">{{ $tripStats['pending_deposits'] }}</div>
                                    <div class="text-xs font-medium text-amber-700 dark:text-amber-300">Pending</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-indigo-100 dark:bg-indigo-900/40 border-2 border-indigo-300 dark:border-indigo-500 p-4 rounded-lg shadow-sm">
                            <div class="text-lg font-bold text-indigo-900 dark:text-indigo-100 mb-3">💳 Remaining (80%)</div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-600 rounded-lg p-2">
                                    <div class="text-xl font-bold text-emerald-800 dark:text-emerald-100">{{ $tripStats['confirmed_remaining'] }}</div>
                                    <div class="text-xs font-medium text-emerald-700 dark:text-emerald-300">Confirmed</div>
                                </div>
                                <div class="text-center bg-amber-50 dark:bg-amber-900/50 border border-amber-200 dark:border-amber-600 rounded-lg p-2">
                                    <div class="text-xl font-bold text-amber-800 dark:text-amber-100">{{ $tripStats['pending_remaining'] }}</div>
                                    <div class="text-xs font-medium text-amber-700 dark:text-amber-300">Pending</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Payment Confirmations -->
            @if($pendingPayments->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            🕐 Pending Payment Confirmations ({{ $pendingPayments->count() }})
                        </h3>
                        <button onclick="toggleBulkConfirm()" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition text-sm">
                            Bulk Confirm
                        </button>
                    </div>
                </div>

                <!-- Bulk Confirm Form (Hidden by default) -->
                <div id="bulk-confirm-form" class="hidden border-b border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900">
                    <form action="{{ route('admin.payment-confirmation.bulk-confirm', $trip) }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            @foreach($pendingPayments as $payment)
                            <div class="flex items-center space-x-3 p-3 bg-white dark:bg-gray-800 rounded-lg">
                                <input type="checkbox" name="selected_payments[]" value="{{ $payment->id }}" 
                                       class="bulk-checkbox rounded border-gray-300 dark:border-gray-600">
                                <div class="flex-1">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $payment->user->username }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">HK$ {{ number_format($payment->amount, 2) }}</span>
                                </div>
                                <input type="hidden" name="confirmations[{{ $loop->index }}][trip_join_id]" value="{{ $payment->id }}">
                                <input type="text" name="confirmations[{{ $loop->index }}][reference_code]" 
                                       placeholder="Reference Code" 
                                       class="w-32 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm dark:bg-gray-700 dark:text-gray-200"
                                       data-payment-id="{{ $payment->id }}">
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-end space-x-2">
                            <button type="button" onclick="toggleBulkConfirm()" 
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                                Confirm Selected
                            </button>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pickup Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($pendingPayments as $payment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-amber-200 dark:bg-amber-700 border-2 border-amber-400 dark:border-amber-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-amber-900 dark:text-amber-100 font-bold text-sm">
                                                {{ substr($payment->user->username, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $payment->user->username }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $payment->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full border-2 {{ $payment->type === 'deposit' ? 'bg-orange-200 text-orange-900 border-orange-400 dark:bg-orange-800 dark:text-orange-100 dark:border-orange-500' : 'bg-indigo-200 text-indigo-900 border-indigo-400 dark:bg-indigo-800 dark:text-indigo-100 dark:border-indigo-500' }}">
                                        {{ $payment->type === 'deposit' ? '💰 DEPOSIT' : '💳 REMAINING' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        HK$ {{ number_format($payment->amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $payment->pickup_location ?: 'Not specified' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $payment->created_at->format('Y-m-d H:i') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.payment-confirmation.show', $payment) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                        Confirm Payment
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Confirmed Payments -->
            @if($confirmedPayments->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        ✅ Confirmed Payments ({{ $confirmedPayments->count() }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reference Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Confirmed At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($confirmedPayments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-emerald-200 dark:bg-emerald-700 border-2 border-emerald-400 dark:border-emerald-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-emerald-900 dark:text-emerald-100 font-bold text-sm">
                                                {{ substr($payment->user->username, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $payment->user->username }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $payment->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full border-2 {{ $payment->type === 'deposit' ? 'bg-orange-200 text-orange-900 border-orange-400 dark:bg-orange-800 dark:text-orange-100 dark:border-orange-500' : 'bg-indigo-200 text-indigo-900 border-indigo-400 dark:bg-indigo-800 dark:text-indigo-100 dark:border-indigo-500' }}">
                                        {{ $payment->type === 'deposit' ? '💰 DEPOSIT' : '💳 REMAINING' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        HK$ {{ number_format($payment->amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-mono font-bold">
                                        {{ $payment->reference_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $payment->updated_at->format('Y-m-d H:i') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($pendingPayments->count() === 0 && $confirmedPayments->count() === 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="text-gray-400 dark:text-gray-500 text-lg mb-2">📭</div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No Payment Records</h3>
                    <p class="text-gray-600 dark:text-gray-400">There are no payment records for this trip yet.</p>
                </div>
            </div>
            @endif
    </div>

    <script>
        function toggleBulkConfirm() {
            const form = document.getElementById('bulk-confirm-form');
            form.classList.toggle('hidden');
        }

        // Auto-populate reference codes based on pattern
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.bulk-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const paymentId = this.value;
                    const referenceInput = document.querySelector(`input[data-payment-id="${paymentId}"]`);
                    
                    if (this.checked && !referenceInput.value) {
                        // Auto-generate reference code suggestion
                        const now = new Date();
                        const timestamp = now.toISOString().slice(2, 10).replace(/-/g, '');
                        referenceInput.value = `REF${paymentId}${timestamp}`;
                    }
                });
            });
        });
    </script>
@endsection