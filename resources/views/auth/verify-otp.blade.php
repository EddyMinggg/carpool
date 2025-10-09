<x-guest-layout>

    <div class="flex flex-col sm:justify-center items-center bg-gray-100 dark:bg-gray-900 mt-8">
        <div class="w-full p-8 bg-secondary dark:bg-secondary-accent overflow-hidden">

            <form id="otp-verification-form">
                @csrf

                <div class="text-center mb-6">
                    <div
                        class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-primary dark:bg-primary-dark">
                        <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mt-4">
                        {{ __('Verify Your Phone Number') }}
                    </h3>
                    <p class="mt-4 text-sm text-gray-400 leading-7">
                        {{ __('We sent a 6-digit verification code to') }}
                        <br>
                        <strong id="verification-phone" class="text-primary"> {{ Auth::user()->phone }}</strong>
                    </p>
                </div>

                <div id="otp-messages" class="hidden mb-4"></div>

                <!-- OTP Input -->
                <div class="mt-4">
                    <div class="flex justify-center space-x-2 mt-2">
                        <x-text-input type="text" id="otp-1"
                            class="otp-digit w-12 h-12 text-center text-lg font-semibold " maxlength="1"
                            pattern="[0-9]" />
                        <x-text-input type="text" id="otp-2"
                            class="otp-digit w-12 h-12 text-center text-lg font-semibold " maxlength="1"
                            pattern="[0-9]" />
                        <x-text-input type="text" id="otp-3"
                            class="otp-digit w-12 h-12 text-center text-lg font-semibold " maxlength="1"
                            pattern="[0-9]" />
                        <x-text-input type="text" id="otp-4"
                            class="otp-digit w-12 h-12 text-center text-lg font-semibold " maxlength="1"
                            pattern="[0-9]" />
                        <x-text-input type="text" id="otp-5"
                            class="otp-digit w-12 h-12 text-center text-lg font-semibold " maxlength="1"
                            pattern="[0-9]" />
                        <x-text-input type="text" id="otp-6"
                            class="otp-digit w-12 h-12 text-center text-lg font-semibold " maxlength="1"
                            pattern="[0-9]" />
                    </div>
                    <div id="otp-error" class="text-red-600 text-sm mt-1 text-center hidden"></div>
                </div>

                <!-- Resend OTP -->
                <div class="text-center mt-8">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __("Didn't receive the code?") }}
                        <button type="button" id="resend-otp-btn"
                            class="text-primary hover:text-primary-accent font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="resend-text ms-1">{{ __('Resend') }}</span>
                            <span class="resend-countdown ms-1 hidden">{{ __('Resend in') }} <span
                                    id="countdown">60</span>s</span>
                        </button>
                    </p>
                </div>

                <div class="flex items-center justify-center mt-6">

                    <x-primary-button id="verify-otp-btn" type="button"
                        class="w-48 justify-center disabled:opacity-50">
                        <span class="verify-otp-text">{{ __('Verify') }}</span>
                        <svg class="verify-otp-spinner animate-spin h-4 w-4 text-white hidden"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <div class="verify-success-animation hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="checkmark h-8 w-8">
                                <path class="checkmark__check" fill="none" stroke="#fff" stroke-width="1.5"
                                    d="M6 12l4 4 8-8" />
                            </svg>
                        </div>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

</x-guest-layout>

<style>
    .checkmark {
        display: inline-block;
        stroke-miterlimit: 10;
        margin: -10px;
        animation: scale .3s ease-in-out .9s both;
    }

    @keyframes scale {

        0%,
        100% {
            transform: none;
        }

        50% {
            transform: scale3d(1.2, 1.2, 1);
        }
    }
</style>

<script type="module">
    $(document).ready(function() {

        function showMessage(containerId, message, type = 'error') {
            const container = document.getElementById(containerId);
            const colorClasses = type === 'error' ?
                'bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200 border-red-200 dark:border-red-800' :
                'bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-200 border-green-200 dark:border-green-800';

            container.innerHTML = `
                <div class="border px-4 py-3 rounded-lg ${colorClasses} message-fade-in">
                    ${message}
                </div>
            `;
            container.classList.remove('hidden');
        }

        function hideMessage(containerId) {
            const container = document.getElementById(containerId);
            container.classList.add('hidden');
            container.innerHTML = '';
        }

        function setupOtpInputs() {
            $('.otp-digit').each(function(index) {
                $(this).on('input', function(e) {
                    // Only allow digits
                    $(this).val($(this).val().replace(/[^0-9]/g, ''));

                    // Move to next input if digit entered
                    if ($(this).val() && index < $('.otp-digit').length - 1) {
                        $('.otp-digit').eq(index + 1).focus();
                    }

                    // Remove error styling
                    $(this).removeClass('error');

                    // Auto-submit when all digits filled
                    const allFilled = $('.otp-digit').toArray().every(input => $(input).val());
                    if (allFilled) {
                        setTimeout(() => {
                            $('#verify-otp-btn').click();
                        }, 300);
                    }
                });

                $(this).on('keydown', function(e) {
                    // Handle backspace
                    if (e.key === 'Backspace' && !$(this).val() && index > 0) {
                        $('.otp-digit').eq(index - 1).focus();
                    }

                    // Handle paste
                    if (e.key === 'v' && (e.ctrlKey || e.metaKey)) {
                        e.preventDefault();
                        navigator.clipboard.readText().then(text => {
                            const digits = text.replace(/[^0-9]/g, '').slice(0, 6);
                            digits.split('').forEach((digit, i) => {
                                if ($('.otp-digit').eq(i)) {
                                    $('.otp-digit').eq(i).val(digit);
                                }
                            });
                            if (digits.length === 6) {
                                setTimeout(() => {
                                    $('#verify-otp-btn').click();
                                }, 300);
                            }
                        });
                    }
                });
            });
        }

        function startResendCountdown(seconds = 60) {
            let resendCountdown = seconds;
            const resendBtn = $('#resend-otp-btn');
            const resendText = resendBtn.find('.resend-text');
            const countdownText = resendBtn.find('.resend-countdown');
            const countdownSpan = $('#countdown');

            resendBtn.prop('disabled', true);
            resendText.addClass('hidden');
            countdownText.removeClass('hidden');

            const resendTimer = setInterval(() => {
                resendCountdown--;
                countdownSpan.text(resendCountdown);

                if (resendCountdown <= 0) {
                    clearInterval(resendTimer);
                    resendBtn.prop('disabled', false);
                    resendText.removeClass('hidden');
                    countdownText.addClass('hidden');
                }
            }, 1000);
        }

        setupOtpInputs();

        // Verify OTP Button Click Event
        $('#verify-otp-btn').on('click', async function() {
            const btn = $(this);
            const btnText = btn.find('.verify-otp-text');
            const spinner = btn.find('.verify-otp-spinner');
            const successAnimation = btn.find('.verify-success-animation');

            // Collect OTP
            const otpInputs = $('.otp-digit');
            const otp = Array.from(otpInputs).map(input => $(input).val()).join('');

            if (otp.length !== 6) {
                showMessage('otp-messages', 'Please enter the complete 6-digit verification code.',
                    'error');
                otpInputs.addClass('error');
                return;
            }

            // Show loading state
            btn.prop('disabled', true);
            btnText.addClass('hidden');
            spinner.removeClass('hidden');
            hideMessage('otp-messages');

            $.ajax({
                url: '{{ route('otp.verify') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    otp_code: otp,
                },
                success: function(data) {
                    if (!data.success) {
                        showMessage('otp-messages', data.message ||
                            'Invalid verification code. Please try again.', 'error');
                        $('.otp-digit').addClass('error').val('');
                        $('#otp-1').focus();
                        btn.prop('disabled', false);
                        btnText.removeClass('hidden');
                        spinner.addClass('hidden');
                    } else {
                        spinner.addClass('hidden');
                        successAnimation.removeClass('hidden');
                        setTimeout(() => {
                            $(location).attr('href',
                                "{{ route('dashboard') }}");
                        }, 2000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Verify OTP Error:', error);
                    showMessage('otp-messages',
                        'Network error. Please check your connection and try again.',
                        'error');
                    btn.prop('disabled', false);
                    btnText.removeClass('hidden');
                    spinner.addClass('hidden');
                },
            });
        });

        // Resend OTP Button Click Event
        $('#resend-otp-btn').on('click', function() {
            if ($(this).prop('disabled')) return;

            hideMessage('otp-messages');

            $.ajax({
                url: '{{ route('otp.resend') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(data) {
                    // Handle the response data
                    if (data.success) {
                        showMessage('otp-messages',
                            'A new verification code has been sent to your phone.',
                            'success');
                        startResendCountdown(60);

                        // Clear OTP inputs
                        $('.otp-digit').val('').removeClass('error success');
                        $('#otp-1').focus();
                    } else {
                        showMessage('otp-messages', data.message ||
                            'Failed to resend verification code. Please try again.',
                            'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);

                    showMessage('otp-messages',
                        'Network error. Please check your connection and try again.',
                        'error');
                }
            });
        });
    });
</script>
