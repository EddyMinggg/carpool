@extends('admin.layout')

@section('title', 'Confirm Payment - ' . $payment->user->username)
@section('page-title', 'Confirm Payment')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Confirm Payment</h2>
            <p class="text-gray-600">User: {{ $payment->user->username }} ({{ ucfirst($payment->type) }})</p>
        </div>
        <a href="{{ route('admin.payment-confirmation.index', $payment->trip) }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            Back to List
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
            @if (session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-100 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- User & Trip Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Trip & User Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Info -->
                        <div class="space-y-4">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300">User Information</h4>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Username</label>
                                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $payment->user->username }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->user->email }}</p>
                            </div>
                            @if($payment->user->phone_number)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Phone</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->user->phone_number }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Type</label>
                                <p class="text-gray-900 dark:text-gray-100">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full border-2 {{ $payment->type === 'deposit' ? 'bg-orange-200 text-orange-900 border-orange-400 dark:bg-orange-800 dark:text-orange-100 dark:border-orange-500' : 'bg-indigo-200 text-indigo-900 border-indigo-400 dark:bg-indigo-800 dark:text-indigo-100 dark:border-indigo-500' }}">
                                        {{ $payment->type === 'deposit' ? '💰 DEPOSIT (20%)' : '💳 REMAINING (80%)' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Created</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>

                        <!-- Trip Info -->
                        <div class="space-y-4">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300">Trip Information</h4>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Trip ID</label>
                                <p class="text-gray-900 dark:text-gray-100 font-medium">#{{ $payment->trip->id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Destination</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->trip->dropoff_location }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Departure Time</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->trip->planned_departure_time->format('Y-m-d H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Amount</label>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">HK$ {{ number_format($payment->amount, 2) }}</p>
                            </div>
                            @if($payment->pickup_location)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Pickup Location</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $payment->pickup_location }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Confirmation Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Confirm Payment Receipt</h3>
                    
                    <form action="{{ route('admin.payment-confirmation.confirm', $payment) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Reference Code -->
                        <div>
                            <label for="reference_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Payment Reference Code *
                            </label>
                            <input type="text" 
                                   name="reference_code" 
                                   id="reference_code" 
                                   value="{{ old('reference_code') }}"
                                   placeholder="Enter bank transfer reference or transaction ID"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200"
                                   required>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Enter the reference code from the user's payment proof (bank transfer, e-wallet, etc.)
                            </p>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Additional Notes (Optional)
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3"
                                      placeholder="Any additional notes about this payment confirmation"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Email Notification Preview -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 dark:text-blue-200 mb-2">📧 Email Notification Preview</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                After confirming, an email will be sent to <strong>{{ $payment->user->email }}</strong> with:
                            </p>
                            <ul class="mt-2 text-sm text-blue-600 dark:text-blue-400 space-y-1">
                                <li>• {{ ucfirst($payment->type) }} payment confirmation for trip #{{ $payment->trip->id }}</li>
                                <li>• Payment amount: HK$ {{ number_format($payment->amount, 2) }}</li>
                                <li>• Trip details and departure time</li>
                                <li>• Further instructions for the trip</li>
                            </ul>
                        </div>

                        <!-- Confirmation Checkbox -->
                        <div class="flex items-start">
                            <input type="checkbox" 
                                   name="confirm_payment" 
                                   id="confirm_payment" 
                                   value="1"
                                   class="mt-1 rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50"
                                   required>
                            <label for="confirm_payment" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                I confirm that I have verified the payment proof and the payment amount is correct. An email notification will be sent to the user.
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.payment-confirmation.index', $payment->trip) }}" 
                               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                ✅ Confirm Payment & Send Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3">Quick Actions</h4>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.trips.show', $payment->trip) }}" 
                       class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 text-sm rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900 transition">
                        🚗 View Trip Details
                    </a>
                    <a href="{{ route('admin.users.show', $payment->user) }}" 
                       class="inline-flex items-center px-3 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200 text-sm rounded-lg hover:bg-purple-200 dark:hover:bg-purple-900 transition">
                        👤 View User Profile
                    </a>
                    <a href="{{ route('admin.payment-confirmation.index', $payment->trip) }}" 
                       class="inline-flex items-center px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 text-sm rounded-lg hover:bg-green-200 dark:hover:bg-green-900 transition">
                        💰 All Trip Payments
                    </a>
                </div>
            </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const referenceInput = document.getElementById('reference_code');
            const confirmCheckbox = document.getElementById('confirm_payment');
            
            // Auto-generate reference code suggestion
            function generateReferenceCode() {
                if (!referenceInput.value) {
                    const now = new Date();
                    const timestamp = now.toISOString().slice(2, 10).replace(/-/g, '');
                    const tripId = "{{ $payment->trip->id }}";
                    const userId = "{{ $payment->user->id }}";
                    const paymentId = "{{ $payment->id }}";
                    referenceInput.value = `REF${tripId}${userId}P${paymentId}${timestamp}`;
                }
            }

            // Focus on reference code input
            referenceInput.focus();

            // Generate reference code suggestion on focus if empty
            referenceInput.addEventListener('focus', generateReferenceCode);

            // Form validation
            form.addEventListener('submit', function(e) {
                if (!confirmCheckbox.checked) {
                    e.preventDefault();
                    alert('Please confirm that you have verified the payment proof.');
                    confirmCheckbox.focus();
                    return false;
                }

                if (!referenceInput.value.trim()) {
                    e.preventDefault();
                    alert('Please enter a payment reference code.');
                    referenceInput.focus();
                    return false;
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '⏳ Confirming Payment...';
            });
        });
    </script>
@endsection