<x-guest-layout>
    <!-- CSRF Token for JavaScript -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            
            <!-- Header -->
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ __('Verify Phone Number') }}
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('We have sent a 6-digit verification code to') }}
                </p>
                <p class="font-semibold text-gray-900 dark:text-gray-100">
                    {{ session('otp_phone') }}
                </p>
                
                @if (app()->environment('local'))
                    <!-- Development Mode: Show Current OTP -->
                    @php
                        $otpFile = storage_path('logs/current_otp.txt');
                        $currentOtp = '';
                        if (file_exists($otpFile)) {
                            $otpContent = file_get_contents($otpFile);
                            if (preg_match('/OTP Code: (\d+)/', $otpContent, $matches)) {
                                $currentOtp = $matches[1];
                            }
                        }
                    @endphp
                    
                    @if ($currentOtp)
                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                <strong>🚧 Development Mode</strong>
                            </p>
                            <p class="text-lg font-mono font-bold text-yellow-900 dark:text-yellow-100">
                                Current OTP: {{ $currentOtp }}
                            </p>
                            <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">
                                (This is only shown in development mode)
                            </p>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- OTP Form -->
            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf

                <!-- OTP Code Input -->
                <div class="mb-6">
                    <x-input-label for="otp_code" :value="__('Verification Code')" />
                    <div class="mt-2">
                        <x-text-input 
                            id="otp_code" 
                            class="block w-full text-center text-2xl font-mono tracking-widest" 
                            type="text" 
                            name="otp_code" 
                            maxlength="6"
                            placeholder="123456"
                            required 
                            autofocus 
                            autocomplete="one-time-code" />
                    </div>
                    <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
                    
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-center">
                        {{ __('Enter the 6-digit code sent to your phone') }}
                    </p>
                </div>

                <!-- Countdown Timer -->
                <div id="countdown-container" class="mb-4 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Code expires in') }} 
                        <span id="countdown" class="font-semibold text-orange-600 dark:text-orange-400">5:00</span>
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="mb-4">
                    <x-primary-button class="w-full justify-center">
                        {{ __('Verify Code') }}
                    </x-primary-button>
                </div>

                <!-- Resend Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __("Didn't receive the code?") }}
                    </p>
                    <button 
                        type="button" 
                        id="resend-btn"
                        class="mt-1 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                        onclick="resendOtp()"
                    >
                        {{ __('Resend Code') }}
                    </button>
                </div>
            </form>

            <!-- Back to Register -->
            <div class="mt-6 text-center border-t border-gray-200 dark:border-gray-700 pt-4">
                <a href="{{ route('register') }}" 
                   class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 font-medium">
                    {{ __('← Back to Registration') }}
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-format OTP input
    const otpInput = document.getElementById('otp_code');
    
    otpInput.addEventListener('input', function(e) {
        // Only allow numbers
        this.value = this.value.replace(/\D/g, '');
        
        // Auto-submit when 6 digits are entered
        if (this.value.length === 6) {
            // Small delay to show the complete code
            setTimeout(() => {
                this.closest('form').submit();
            }, 300);
        }
    });

    // Countdown timer (5 minutes)
    let timeLeft = 300; // 5 minutes in seconds
    const countdownElement = document.getElementById('countdown');
    const resendBtn = document.getElementById('resend-btn');
    
    function updateCountdown() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            countdownElement.textContent = '{{ __('Expired') }}';
            countdownElement.className = 'font-semibold text-red-600 dark:text-red-400';
            resendBtn.disabled = false;
            resendBtn.textContent = '{{ __('Send New Code') }}';
            return;
        }
        
        // Change color when time is running low
        if (timeLeft <= 60) {
            countdownElement.className = 'font-semibold text-red-600 dark:text-red-400';
        } else if (timeLeft <= 180) {
            countdownElement.className = 'font-semibold text-yellow-600 dark:text-yellow-400';
        }
        
        timeLeft--;
        setTimeout(updateCountdown, 1000);
    }
    
    updateCountdown();
});

function resendOtp() {
    const resendBtn = document.getElementById('resend-btn');
    
    // Disable button and show loading
    resendBtn.disabled = true;
    resendBtn.textContent = '{{ __('Sending...') }}';
    
    // Make request to resend OTP
    fetch('{{ route('otp.resend') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reset countdown
            timeLeft = 300;
            document.getElementById('countdown').className = 'font-semibold text-orange-600 dark:text-orange-400';
            
            // Show success message
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message || '{{ __('Failed to resend code. Please try again.') }}', 'error');
        }
    })
    .catch(error => {
        showMessage('{{ __('An error occurred. Please try again.') }}', 'error');
    })
    .finally(() => {
        resendBtn.disabled = false;
        resendBtn.textContent = '{{ __('Resend Code') }}';
    });
}

function showMessage(message, type) {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.temp-message');
    existingMessages.forEach(msg => msg.remove());
    
    // Create new message element
    const messageDiv = document.createElement('div');
    messageDiv.className = `temp-message mb-4 px-4 py-3 rounded-lg ${
        type === 'success' 
            ? 'bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200'
            : 'bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200'
    }`;
    messageDiv.textContent = message;
    
    // Insert message at the top of the form
    const form = document.querySelector('form');
    form.insertBefore(messageDiv, form.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        messageDiv.remove();
    }, 5000);
}
</script>

<style>
/* Custom styling for OTP input */
#otp_code {
    letter-spacing: 0.2em;
}

#otp_code::placeholder {
    letter-spacing: 0.1em;
    opacity: 0.5;
}

/* Disable number input arrows */
#otp_code::-webkit-outer-spin-button,
#otp_code::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

#otp_code[type=number] {
    -moz-appearance: textfield;
}
</style>