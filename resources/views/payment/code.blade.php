@section('Title', __('Payment'))

<x-app-layout>
    <x-slot name="header">
        <button onclick="window.location='{{ route('dashboard') }}'" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            <span class="material-icons text-gray-700 dark:text-gray-300">arrow_back</span>
        </button>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 pb-12">
        <div class="p-4">
            <!-- Payment Countdown Timer -->
            <div id="countdown-container" class="bg-red-50 dark:bg-red-900/20 border-red-500 dark:border-red-400 rounded-r-lg p-4 mb-4">
                <div class="text-center">
                    <div class="flex items-center justify-center mb-2">
                        <span class="text-red-500 dark:text-red-400 mr-2 text-lg">⏰</span>
                        <span class="text-sm font-semibold text-red-700 dark:text-red-300">
                            {{ __('Payment Deadline') }}
                        </span>
                    </div>
                    <div id="countdown-timer" class="text-5xl font-mono font-bold text-red-800 dark:text-red-200 tracking-wide">
                        --:--
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-100">
                            {{ __('Payment Status: Pending') }} - {{ __('Waiting for admin confirmation') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <h2 class="text-lg text-gray-900 dark:text-gray-300 font-black">
                {{ __('Make your deposit payment.') }}
            </h2>
            
            <!-- Trip Information -->
            <div class="mt-6 bg-secondary dark:bg-secondary-accent border border-gray-200 dark:border-gray-800 rounded-lg p-4 shadow-sm">
                <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-3">{{ __('Trip Details') }}</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">{{ __('From') }}</span>
                        <span class="text-gray-700 dark:text-gray-200 font-semibold">{{ $payment->pickup_location }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">{{ __('To') }}</span>
                        <span class="text-gray-700 dark:text-gray-200 font-semibold" style="margin-top: 0.1rem;">{{ $payment->trip->dropoff_location }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">{{ __('Departure Time') }}</span>
                        <span class="text-gray-700 dark:text-gray-200 font-semibold">{{ $payment->trip->planned_departure_time->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-600">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">{{ __('Deposit Amount') }}</span>
                        <span class="text-gray-700 dark:text-gray-200 font-bold text-lg text-blue-600 dark:text-blue-400">HK$ {{ number_format($payment->amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 sm:mx-0 text-md text-gray-900 dark:text-gray-300">
                <ul class="list-disc list-inside">
                    <li>
                        {{ __('Scan the QR Code below.') }}
                    </li>
                    <li>
                        <span class="font-normal">
                            {{ __('Pay the required amount: ') }}
                        </span>
                        <span class="font-black underline">
                            {{ '$' . $payment->amount }}
                        </span>
                    </li>
                    <li>
                        <span>
                            {{ __('Enter the reference code as the note of the transaction.') }}
                        </span>
                    </li>
                </ul>
            </div>
            <div class="w-full mt-6">
                <div class="relative">
                    <x-input-label for="reference-copy-button">
                        {{ __('Reference Code') }}
                    </x-input-label>
                    <x-text-input id="reference-copy-button" class="mt-2 w-full p-3.5" value="{{ $payment->reference_code }}"
                        disabled readonly />
                    <button data-copy-to-clipboard-target="reference-copy-button"
                        data-tooltip-target="tooltip-copy-reference-copy-button"
                        class="mt-1 absolute end-2 top-4 translate-y-5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg p-2 inline-flex items-center justify-center">
                        <span id="default-icon">
                            <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 18 20">
                                <path
                                    d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
                            </svg>
                        </span>
                        <span id="success-icon" class="hidden">
                            <svg class="w-3.5 h-3.5 text-blue-700 dark:text-blue-500" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                        </span>
                    </button>
                    <div id="tooltip-copy-reference-copy-button" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                        <span id="default-tooltip-message">Copy to clipboard</span>
                        <span id="success-tooltip-message" class="hidden">Copied!</span>
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <div class="w-full mt-8 flex justify-center">
                <img class="w-full md:w-96 object-contain" src="{{ asset('img/payme_code.jpg') }}" />
            </div>
            
            <!-- Manual refresh button -->
            <div class="w-full mt-6 flex justify-center">
                <button id="check-status-btn" 
                    class="bg-primary dark:bg-primary-dark hover:bg-primary-accent dark:hover:bg-primary disabled:bg-primary-dark dark:disabled:bg-primary-darker text-gray-200 px-6 py-3 rounded-xl font-semibold transition shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    {{ __('Check Payment Status') }}
                </button>
            </div>
            
            <!-- Payment Instructions -->
            <div class="mt-8 bg-yellow-50 dark:bg-yellow-900/40 border border-yellow-200 dark:border-yellow-600 rounded-lg p-4 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-50">
                            {{ __('Important Instructions') }}
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-100">
                            <p>{{ __('After completing the payment, please wait for admin confirmation. The page will automatically refresh when your payment is approved.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    window.addEventListener('load', function() {
        // Payment deadline countdown timer
        const paymentCreatedAt = new Date("{{ $payment->created_at->toISOString() }}");
        const paymentDeadline = new Date(paymentCreatedAt.getTime() + (30 * 60 * 1000)); // 30 minutes from creation
        
        const countdownTimer = document.getElementById('countdown-timer');
        const countdownProgress = document.getElementById('countdown-progress');
        const countdownContainer = document.getElementById('countdown-container');
        
        function updateCountdown() {
            const now = new Date();
            const timeLeft = paymentDeadline - now;
            
            if (timeLeft <= 0) {
                // Time's up
                countdownTimer.textContent = '{{ __("EXPIRED") }}';
                countdownContainer.className = 'bg-gray-50 dark:bg-gray-800 border-l-2 border-gray-400 dark:border-gray-500 rounded-r-lg p-3 mb-4';
                countdownContainer.innerHTML = `
                    <div class="flex items-center">
                        <span class="text-gray-500 mr-2">⏰</span>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                            {{ __('Payment deadline has passed. Please contact support.') }}
                        </span>
                    </div>
                `;
                return false; // Stop the timer
            }
            
            // Calculate minutes and seconds
            const minutes = Math.floor(timeLeft / 60000);
            const seconds = Math.floor((timeLeft % 60000) / 1000);
            
            // Update timer display
            countdownTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Change colors based on time remaining
            if (minutes <= 5) {
                // Last 5 minutes - critical (pulsing red)
                countdownContainer.className = 'bg-red-100 dark:bg-red-900/40 border-l-2 border-red-600 dark:border-red-400 rounded-r-lg p-4 mb-4 animate-pulse';
                countdownTimer.className = 'text-6xl font-mono font-bold text-red-900 dark:text-red-100 tracking-wide';
            } else if (minutes <= 10) {
                // Last 10 minutes - warning (orange)
                countdownContainer.className = 'bg-orange-50 dark:bg-orange-900/30 border-l-2 border-orange-500 dark:border-orange-400 rounded-r-lg p-4 mb-4';
                countdownTimer.className = 'text-5xl font-mono font-bold text-orange-900 dark:text-orange-100 tracking-wide';
            } else {
                // Normal state (red)
                countdownContainer.className = 'bg-red-50 dark:bg-red-900/20 border-l-2 border-red-500 dark:border-red-400 rounded-r-lg p-4 mb-4';
                countdownTimer.className = 'text-5xl font-mono font-bold text-red-800 dark:text-red-200 tracking-wide';
            }
            
            return true; // Continue the timer
        }
        
        // Start the countdown
        updateCountdown();
        const countdownInterval = setInterval(() => {
            if (!updateCountdown()) {
                clearInterval(countdownInterval);
            }
        }, 1000);

        const clipboard = FlowbiteInstances.getInstance('CopyClipboard', 'reference-copy-button');
        const tooltip = FlowbiteInstances.getInstance('Tooltip', 'tooltip-copy-reference-copy-button');

        const $defaultIcon = document.getElementById('default-icon');
        const $successIcon = document.getElementById('success-icon');

        const $defaultTooltipMessage = document.getElementById('default-tooltip-message');
        const $successTooltipMessage = document.getElementById('success-tooltip-message');

        clipboard.updateOnCopyCallback((clipboard) => {
            showSuccess();

            // reset to default state
            setTimeout(() => {
                resetToDefault();
            }, 2000);
        })

        const showSuccess = () => {
            $defaultIcon.classList.add('hidden');
            $successIcon.classList.remove('hidden');
            $defaultTooltipMessage.classList.add('hidden');
            $successTooltipMessage.classList.remove('hidden');
            tooltip.show();
        }

        const resetToDefault = () => {
            $defaultIcon.classList.remove('hidden');
            $successIcon.classList.add('hidden');
            $defaultTooltipMessage.classList.remove('hidden');
            $successTooltipMessage.classList.add('hidden');
            tooltip.hide();
        }

        // Auto-refresh to check payment status every 30 seconds
        let checkPaymentStatus = function() {
            fetch(window.location.href, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                // If the response is a redirect (payment confirmed), reload the page
                if (response.redirected) {
                    window.location.href = response.url;
                }
            })
            .catch(error => {
                console.log('Payment status check failed:', error);
            });
        };

        // Check payment status every 30 seconds
        setInterval(checkPaymentStatus, 30000);

        // Manual check button functionality
        document.getElementById('check-status-btn').addEventListener('click', function() {
            const button = this;
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('Checking...') }}
            `;
            button.disabled = true;
            
            // Check payment status
            fetch(window.location.href, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.redirected) {
                    // Payment confirmed, redirect
                    window.location.href = response.url;
                } else {
                    // Still pending, show feedback
                    button.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('Still Pending') }}
                    `;
                    
                    // Reset button after 3 seconds
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 3000);
                }
            })
            .catch(error => {
                console.log('Manual payment check failed:', error);
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });

        // No status indicator needed - keep it clean
    })
</script>