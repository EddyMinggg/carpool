<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex flex-col justify-center items-end">
        <input type="checkbox" name="light-switch" class="light-switch sr-only" id="light-switch">
        <label class="relative cursor-pointer p-2" for="light-switch">
            <svg class="dark:hidden" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                <path class="fill-slate-300"
                    d="M7 0h2v2H7zM12.88 1.637l1.414 1.415-1.415 1.413-1.413-1.414zM14 7h2v2h-2zM12.95 14.433l-1.414-1.413 1.413-1.415 1.415 1.414zM7 14h2v2H7zM2.98 14.364l-1.413-1.415 1.414-1.414 1.414 1.415zM0 7h2v2H0zM3.05 1.706 4.463 3.12 3.05 4.535 1.636 3.12z" />
                <path class="fill-slate-400" d="M8 4C5.8 4 4 5.8 4 8s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4Z" />
            </svg>
            <svg class="hidden dark:block" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                <path class="fill-slate-400"
                    d="M6.2 1C3.2 1.8 1 4.6 1 7.9 1 11.8 4.2 15 8.1 15c3.3 0 6-2.2 6.9-5.2C9.7 11.2 4.8 6.3 6.2 1Z" />
                <path class="fill-slate-500"
                    d="M12.5 5a.625.625 0 0 1-.625-.625 1.252 1.252 0 0 0-1.25-1.25.625.625 0 1 1 0-1.25 1.252 1.252 0 0 0 1.25-1.25.625.625 0 1 1 1.25 0c.001.69.56 1.249 1.25 1.25a.625.625 0 1 1 0 1.25c-.69.001-1.249.56-1.25 1.25A.625.625 0 0 1 12.5 5Z" />
            </svg>
        </label>
    </div>

    <div class="relative tab-group">
        <div class="flex border-b border-gray-300 dark:border-gray-600 relative" role="tablist">
            <div
                class="absolute bottom-0 h-0.5 bg-primary dark:bg-primary-dark transition-transform duration-300 transform scale-x-0 translate-x-0 tab-indicator">
            </div>

            <a href="#"
                class="tab-link block font-medium text-sm text-gray-700 dark:text-gray-300 {{ $errors->any() && (old('username') || old('email') || old('phone')) ? '' : 'active' }} inline-block py-2 px-4 hover:text-stone-500 transition-colors duration-300 mr-1"
                data-dui-tab-target="tab1-group4">
                {{ __('Login') }}
            </a>
            <a href="#"
                class="tab-link block font-medium text-sm text-gray-700 dark:text-gray-300 {{ $errors->any() && (old('invitation_code') || old('phone')) ? '' : 'active' }} inline-block py-2 px-4 hover:text-stone-500 transition-colors duration-300 mr-1"
                data-dui-tab-target="tab2-group4">
                {{ __('Join Trip') }}
            </a>
            <a href="#"
                class="tab-link block font-medium text-sm text-gray-700 dark:text-gray-300 {{ $errors->any() && (old('username') || old('email') || old('phone')) ? 'active' : '' }} inline-block py-2 px-4  hover:text-stone-500 transition-colors duration-300 mr-1"
                data-dui-tab-target="tab3-group4">
                {{ __('Register') }}
            </a>
        </div>
        <div class="mt-4 tab-content-container">
            <div id="tab1-group4"
                class="tab-content {{ $errors->any() && (old('username') || old('email') || old('phone')) ? 'hidden' : 'block' }} font-medium text-sm text-gray-700 dark:text-gray-300">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email_login" :value="__('Email')" />
                        <x-text-input id="email_login" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password_login" :value="__('Password')" />

                        <x-text-input id="password_login" class="block mt-1 w-full" type="password" name="password"
                            required autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded bg-secondary dark:bg-secondary-dark border-gray-300 dark:border-gray-700 text-primary shadow-sm focus:ring-primary dark:focus:ring-primary-dark dark:focus:ring-offset-secondary-dark"
                                name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-secondary-dark"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-primary-button class="ms-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            <div id="tab2-group4"
                class="tab-content {{ $errors->any() && (old('invitation_code') || old('phone')) ? 'hidden' : 'block' }} font-medium text-sm text-gray-700 dark:text-gray-300">
                <form method="POST" action="{{ route('guest') }}">
                    @csrf
                    <!-- Email Address -->
                    <div>
                        <x-input-label for="invitation_code" :value="__('Invitation Code')" />
                        <x-text-input id="invitation_code" class="block mt-1 w-full" name="invitation_code"
                            :value="old('invitation_code')" required autofocus />
                        <x-input-error :messages="$errors->get('invitation_code')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="phone_invite" :value="__('Phone Number')" />
                        <div class="flex mt-1">
                            <select id="phone_country_code_invite" name="phone_country_code_invite"
                                class="rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                <option value="+852"
                                    {{ old('phone_country_code', '+852') == '+852' ? 'selected' : '' }}>+852 (HK)
                                </option>
                                <option value="+86" {{ old('phone_country_code') == '+86' ? 'selected' : '' }}>+86
                                    (CN)</option>
                            </select>
                            <x-text-input id="phone_invite" class="block w-full rounded-l-none border-l-0"
                                type="tel" name="phone_invite" :value="old('phone_invite')" required autocomplete="tel"
                                placeholder="12345678" />
                        </div>
                        <x-input-error :messages="$errors->get('phone_invite')" class="mt-2" />
                        <x-input-error :messages="$errors->get('phone_country_code_invite')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            <div id="tab3-group4"
                class="tab-content {{ $errors->any() && (old('username') || old('email') || old('phone')) ? 'block' : 'hidden' }} font-medium text-sm text-gray-700 dark:text-gray-300">

                <!-- Registration Progress Indicator -->
                {{-- <div class="mb-6">
                    <div class="flex items-center justify-center space-x-4">
                        <div class="flex items-center">
                            <div id="step1-indicator"
                                class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-medium">
                                1</div>
                            <span
                                class="ml-2 text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ __('Basic Info') }}</span>
                        </div>
                        <div class="w-12 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                        <div class="flex items-center">
                            <div id="step2-indicator"
                                class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 flex items-center justify-center text-sm font-medium">
                                2</div>
                            <span
                                class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Verify Phone') }}</span>
                        </div>
                        <div class="w-12 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                        <div class="flex items-center">
                            <div id="step3-indicator"
                                class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 flex items-center justify-center text-sm font-medium">
                                3</div>
                            <span
                                class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Complete') }}</span>
                        </div>
                    </div>
                </div> --}}

                <!-- Step 1: Basic Information -->
                <div id="register-step-1" class="register-step block">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <!-- Messages -->
                        <div id="register-messages" class="hidden mb-4"></div>

                        <!-- Name -->
                        <div>
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                                :value="old('username')" required autofocus autocomplete="name" />
                            <div id="username-error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email_register" :value="__('Email')" />
                            <x-text-input id="email_register" class="block mt-1 w-full" type="email"
                                name="email" :value="old('email')" required autocomplete="username" />
                            <div id="email-error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Phone Number -->
                        <div class="mt-4">
                            <x-input-label for="phone_register" :value="__('Phone Number')" />
                            <div class="flex mt-1">
                                <select id="phone_country_code" name="phone_country_code"
                                    class="rounded-l-md border-gray-300 dark:border-gray-700 bg-secondary dark:bg-secondary-dark dark:text-gray-300 focus:border-primary dark:focus:border-primary-dark focus:ring-primary dark:focus:ring-primary-dark shadow-sm">
                                    <option value="+852"
                                        {{ old('phone_country_code', '+852') == '+852' ? 'selected' : '' }}>+852 (HK)
                                    </option>
                                    <option value="+86" {{ old('phone_country_code') == '+86' ? 'selected' : '' }}>
                                        +86 (CN)</option>
                                </select>
                                <x-text-input id="phone_register" class="block w-full rounded-l-none border-l-0"
                                    type="tel" name="phone" :value="old('phone')" required autocomplete="tel"
                                    placeholder="12345678" />
                            </div>
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            <x-input-error :messages="$errors->get('phone_country_code')" class="mt-2" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('We will send you an One-Time-Passcode to verify your phone number.') }}
                            </p>
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password_register" :value="__('Password')" />
                            <x-text-input id="password_register" class="block mt-1 w-full" type="password"
                                name="password" required autocomplete="new-password" />
                            <div id="password-error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                            <div id="password-confirmation-error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button id="send-otp-btn">
                                <span class="send-otp-text">{{ __('Register') }}</span>
                                <svg class="send-otp-spinner animate-spin -mr-1 ml-3 h-4 w-4 text-white hidden"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Step 2: OTP Verification -->
                <div id="register-step-2" class="register-step hidden">
                    <form id="otp-verification-form">
                        @csrf

                        <div class="text-center mb-6">
                            <div
                                class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="mt-3 text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Verify Your Phone Number') }}</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('We sent a 6-digit verification code to') }}
                                <br><strong id="verification-phone"
                                    class="text-indigo-600 dark:text-indigo-400"></strong>
                            </p>
                        </div>

                        <div id="otp-messages" class="hidden mb-4"></div>

                        <!-- OTP Input -->
                        <div class="mt-4">
                            <x-input-label :value="__('Verification Code')" />
                            <div class="flex justify-center space-x-2 mt-2">
                                <input type="text" id="otp-1"
                                    class="otp-digit w-12 h-12 text-center text-lg font-semibold border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    maxlength="1" pattern="[0-9]">
                                <input type="text" id="otp-2"
                                    class="otp-digit w-12 h-12 text-center text-lg font-semibold border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    maxlength="1" pattern="[0-9]">
                                <input type="text" id="otp-3"
                                    class="otp-digit w-12 h-12 text-center text-lg font-semibold border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    maxlength="1" pattern="[0-9]">
                                <input type="text" id="otp-4"
                                    class="otp-digit w-12 h-12 text-center text-lg font-semibold border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    maxlength="1" pattern="[0-9]">
                                <input type="text" id="otp-5"
                                    class="otp-digit w-12 h-12 text-center text-lg font-semibold border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    maxlength="1" pattern="[0-9]">
                                <input type="text" id="otp-6"
                                    class="otp-digit w-12 h-12 text-center text-lg font-semibold border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    maxlength="1" pattern="[0-9]">
                            </div>
                            <div id="otp-error" class="text-red-600 text-sm mt-1 text-center hidden"></div>
                        </div>

                        <!-- Resend OTP -->
                        <div class="text-center mt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __("Didn't receive the code?") }}
                                <button type="button" id="resend-otp-btn"
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="resend-text">{{ __('Resend') }}</span>
                                    <span class="resend-countdown hidden">{{ __('Resend in') }} <span
                                            id="countdown">60</span>s</span>
                                </button>
                            </p>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="button" id="back-to-basic-btn"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 dark:hover:bg-gray-600 focus:bg-gray-500 dark:focus:bg-gray-600 active:bg-gray-900 dark:active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Back') }}
                            </button>

                            <button type="button" id="verify-otp-btn"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 dark:hover:bg-indigo-600 focus:bg-indigo-500 dark:focus:bg-indigo-600 active:bg-indigo-900 dark:active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 disabled:opacity-50">
                                <span class="verify-otp-text">{{ __('Verify & Complete Registration') }}</span>
                                <svg class="verify-otp-spinner animate-spin -mr-1 ml-3 h-4 w-4 text-white hidden"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 3: Success -->
                <div id="register-step-3" class="register-step hidden">
                    <div class="text-center">
                        <div
                            class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="mt-3 text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Registration Completed!') }}</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Your account has been created successfully. You will be redirected to the dashboard shortly.') }}
                        </p>
                        <div class="mt-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>

<style>
    .tab-indicator {
        transition: transform 0.3s ease, width 0.3s ease;
        transform-origin: left;
    }

    .tab-content {
        transition: opacity 0.2s ease;
    }

    .tab-content.hidden {
        display: none !important;
    }

    /* Registration Steps */
    .register-step {
        transition: opacity 0.3s ease;
    }

    .register-step.hidden {
        display: none !important;
    }

    /* OTP Input Styling */
    .otp-digit {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .otp-digit:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .otp-digit.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .otp-digit.success {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Progress Indicator */
    .step-indicator-active {
        background-color: #4f46e5;
        color: white;
    }

    .step-indicator-completed {
        background-color: #10b981;
        color: white;
    }

    .step-indicator-inactive {
        background-color: #d1d5db;
        color: #6b7280;
    }

    /* Animation for messages */
    .message-fade-in {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    const lightSwitches = document.querySelectorAll('.light-switch');
    if (lightSwitches.length > 0) {
        lightSwitches.forEach((lightSwitch, i) => {
            if (localStorage.getItem('dark-mode') === 'true') {
                lightSwitch.checked = true;
            }
            lightSwitch.addEventListener('change', () => {
                const {
                    checked
                } = lightSwitch;
                lightSwitches.forEach((el, n) => {
                    if (n !== i) {
                        el.checked = checked;
                    }
                });
                if (lightSwitch.checked) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('dark-mode', true);
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('dark-mode', false);
                }
            });
        });
    }

    // Tab functionality and error handling
    document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');
        const tabIndicator = document.querySelector('.tab-indicator');

        // Check if we should show register tab (due to validation errors)
        const hasRegisterErrors = @json($errors->any() && (old('username') || old('email') || old('phone')));

        function showTab(targetId, activeLink) {
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('block');
            });

            // Remove active class from all links
            tabLinks.forEach(link => {
                link.classList.remove('active');
            });

            // Show target tab content
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.remove('hidden');
                targetContent.classList.add('block');
            }

            // Add active class to clicked link
            activeLink.classList.add('active');

            // Move tab indicator
            const linkRect = activeLink.getBoundingClientRect();
            const containerRect = activeLink.parentElement.getBoundingClientRect();
            const offsetLeft = linkRect.left - containerRect.left;
            const width = linkRect.width;

            tabIndicator.style.transform = `translateX(${offsetLeft}px) scaleX(1)`;
            tabIndicator.style.width = `${width}px`;
        }

        // Initialize tabs
        if (hasRegisterErrors) {
            // Show register tab if there are register errors
            const registerLink = document.querySelector('[data-dui-tab-target="tab2-group4"]');
            showTab('tab2-group4', registerLink);
        } else {
            // Show login tab by default
            const loginLink = document.querySelector('[data-dui-tab-target="tab1-group4"]');
            showTab('tab1-group4', loginLink);
        }

        // Add click handlers to tab links
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-dui-tab-target');
                showTab(targetId, this);
            });
        });

        // Registration functionality
        // initializeRegistration();
    });

    // // Registration Multi-step functionality
    // function initializeRegistration() {
    //     let registrationData = {};
    //     let resendCountdown = 0;
    //     let resendTimer = null;

    //     // Progress indicator management
    //     function updateProgressIndicator(step) {
    //         const indicators = ['step1-indicator', 'step2-indicator', 'step3-indicator'];

    //         indicators.forEach((id, index) => {
    //             const indicator = document.getElementById(id);
    //             const stepNumber = index + 1;

    //             if (stepNumber < step) {
    //                 // Completed step
    //                 indicator.className = 'w-8 h-8 rounded-full step-indicator-completed flex items-center justify-center text-sm font-medium';
    //                 indicator.innerHTML = '✓';
    //             } else if (stepNumber === step) {
    //                 // Current step
    //                 indicator.className = 'w-8 h-8 rounded-full step-indicator-active flex items-center justify-center text-sm font-medium';
    //                 indicator.innerHTML = stepNumber;
    //             } else {
    //                 // Future step
    //                 indicator.className = 'w-8 h-8 rounded-full step-indicator-inactive flex items-center justify-center text-sm font-medium';
    //                 indicator.innerHTML = stepNumber;
    //             }
    //         });
    //     }

    //     // Step management
    //     function showStep(stepNumber) {
    //         // Hide all steps
    //         document.querySelectorAll('.register-step').forEach(step => {
    //             step.classList.add('hidden');
    //             step.classList.remove('block');
    //         });

    //         // Show target step
    //         const targetStep = document.getElementById(`register-step-${stepNumber}`);
    //         if (targetStep) {
    //             targetStep.classList.remove('hidden');
    //             targetStep.classList.add('block');
    //         }

    //         // Update progress indicator
    //         updateProgressIndicator(stepNumber);
    //     }

    //     // Message display functions
    //     function showMessage(containerId, message, type = 'error') {
    //         const container = document.getElementById(containerId);
    //         const colorClasses = type === 'error' 
    //             ? 'bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200 border-red-200 dark:border-red-800'
    //             : 'bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-200 border-green-200 dark:border-green-800';

    //         container.innerHTML = `
    //             <div class="border px-4 py-3 rounded-lg ${colorClasses} message-fade-in">
    //                 ${message}
    //             </div>
    //         `;
    //         container.classList.remove('hidden');
    //     }

    //     function hideMessage(containerId) {
    //         const container = document.getElementById(containerId);
    //         container.classList.add('hidden');
    //         container.innerHTML = '';
    //     }

    //     function clearFieldErrors() {
    //         document.querySelectorAll('[id$="-error"]').forEach(errorElement => {
    //             errorElement.classList.add('hidden');
    //             errorElement.textContent = '';
    //         });
    //     }

    //     function showFieldErrors(errors) {
    //         clearFieldErrors();
    //         Object.keys(errors).forEach(field => {
    //             const errorElement = document.getElementById(`${field}-error`);
    //             if (errorElement && errors[field][0]) {
    //                 errorElement.textContent = errors[field][0];
    //                 errorElement.classList.remove('hidden');
    //             }
    //         });
    //     }

    //     // OTP digit input handling
        // function setupOtpInputs() {
        //     const otpInputs = document.querySelectorAll('.otp-digit');

        //     otpInputs.forEach((input, index) => {
        //         input.addEventListener('input', function(e) {
        //             // Only allow digits
        //             this.value = this.value.replace(/[^0-9]/g, '');

        //             // Move to next input if digit entered
        //             if (this.value && index < otpInputs.length - 1) {
        //                 otpInputs[index + 1].focus();
        //             }

        //             // Remove error styling
        //             this.classList.remove('error');

        //             // Auto-submit when all digits filled
        //             const allFilled = Array.from(otpInputs).every(input => input.value);
        //             if (allFilled) {
        //                 setTimeout(() => {
        //                     document.getElementById('verify-otp-btn').click();
        //                 }, 300);
        //             }
        //         });

        //         input.addEventListener('keydown', function(e) {
        //             // Handle backspace
        //             if (e.key === 'Backspace' && !this.value && index > 0) {
        //                 otpInputs[index - 1].focus();
        //             }

        //             // Handle paste
        //             if (e.key === 'v' && (e.ctrlKey || e.metaKey)) {
        //                 e.preventDefault();
        //                 navigator.clipboard.readText().then(text => {
        //                     const digits = text.replace(/[^0-9]/g, '').slice(0, 6);
        //                     digits.split('').forEach((digit, i) => {
        //                         if (otpInputs[i]) {
        //                             otpInputs[i].value = digit;
        //                         }
        //                     });
        //                     if (digits.length === 6) {
        //                         setTimeout(() => {
        //                             document.getElementById('verify-otp-btn').click();
        //                         }, 300);
        //                     }
        //                 });
        //             }
        //         });
        //     });
        // }

        // // Resend countdown
        // function startResendCountdown(seconds = 60) {
        //     resendCountdown = seconds;
        //     const resendBtn = document.getElementById('resend-otp-btn');
        //     const resendText = resendBtn.querySelector('.resend-text');
        //     const countdownText = resendBtn.querySelector('.resend-countdown');
        //     const countdownSpan = document.getElementById('countdown');

        //     resendBtn.disabled = true;
        //     resendText.classList.add('hidden');
        //     countdownText.classList.remove('hidden');

        //     resendTimer = setInterval(() => {
        //         resendCountdown--;
        //         countdownSpan.textContent = resendCountdown;

        //         if (resendCountdown <= 0) {
        //             clearInterval(resendTimer);
        //             resendBtn.disabled = false;
        //             resendText.classList.remove('hidden');
        //             countdownText.classList.add('hidden');
        //         }
        //     }, 1000);
        // }

    //     // Step 1: Send OTP
    //     document.getElementById('send-otp-btn').addEventListener('click', async function() {
    //         const btn = this;
    //         const btnText = btn.querySelector('.send-otp-text');
    //         const spinner = btn.querySelector('.send-otp-spinner');

    //         // Collect form data
    //         const formData = new FormData();
    //         formData.append('_token', document.querySelector('input[name="_token"]').value);
    //         formData.append('username', document.getElementById('username').value);
    //         formData.append('email', document.getElementById('email_register').value);
    //         formData.append('phone_country_code', document.getElementById('phone_country_code').value);
    //         formData.append('phone', document.getElementById('phone_register').value);
    //         formData.append('password', document.getElementById('password_register').value);
    //         formData.append('password_confirmation', document.getElementById('password_confirmation').value);

    //         // Store registration data
    //         registrationData = {
    //             username: formData.get('username'),
    //             email: formData.get('email'),
    //             phone_country_code: formData.get('phone_country_code'),
    //             phone: formData.get('phone'),
    //             password: formData.get('password'),
    //             password_confirmation: formData.get('password_confirmation')
    //         };

    //         // Show loading state
    //         btn.disabled = true;
    //         btnText.classList.add('hidden');
    //         spinner.classList.remove('hidden');
    //         hideMessage('register-messages');
    //         clearFieldErrors();

    //         try {
    //             const response = await fetch('{{ route('register.send-otp') }}', {
    //                 method: 'POST',
    //                 body: formData,
    //                 headers: {
    //                     'X-Requested-With': 'XMLHttpRequest',
    //                 }
    //             });

    //             const data = await response.json();

    //             if (data.success) {
    //                 // Show phone number in step 2
    //                 document.getElementById('verification-phone').textContent = 
    //                     registrationData.phone_country_code + registrationData.phone;

    //                 // Move to step 2
    //                 showStep(2);
    //                 setupOtpInputs();
    //                 startResendCountdown(60);

    //                 // Focus first OTP input
    //                 document.getElementById('otp-1').focus();
    //             } else {
    //                 if (data.errors) {
    //                     showFieldErrors(data.errors);
    //                 } else {
    //                     showMessage('register-messages', data.message || '{{ __('Failed to send verification code. Please try again.') }}', 'error');
    //                 }
    //             }
    //         } catch (error) {
    //             console.error('Send OTP Error:', error);
    //             showMessage('register-messages', '{{ __('Network error. Please check your connection and try again.') }}', 'error');
    //         } finally {
    //             // Reset button state
    //             btn.disabled = false;
    //             btnText.classList.remove('hidden');
    //             spinner.classList.add('hidden');
    //         }
    //     });

    //     // Step 2: Verify OTP
    //     document.getElementById('verify-otp-btn').addEventListener('click', async function() {
    //         const btn = this;
    //         const btnText = btn.querySelector('.verify-otp-text');
    //         const spinner = btn.querySelector('.verify-otp-spinner');

    //         // Collect OTP
    //         const otpInputs = document.querySelectorAll('.otp-digit');
    //         const otp = Array.from(otpInputs).map(input => input.value).join('');

    //         if (otp.length !== 6) {
    //             showMessage('otp-messages', '{{ __('Please enter the complete 6-digit verification code.') }}', 'error');
    //             otpInputs.forEach(input => input.classList.add('error'));
    //             return;
    //         }

    //         // Show loading state
    //         btn.disabled = true;
    //         btnText.classList.add('hidden');
    //         spinner.classList.remove('hidden');
    //         hideMessage('otp-messages');

    //         // Prepare registration data
    //         const formData = new FormData();
    //         Object.keys(registrationData).forEach(key => {
    //             formData.append(key, registrationData[key]);
    //         });
    //         formData.append('_token', document.querySelector('input[name="_token"]').value);
    //         formData.append('otp_code', otp);

    //         try {
    //             const response = await fetch('{{ route('register.verify-otp') }}', {
    //                 method: 'POST',
    //                 body: formData,
    //                 headers: {
    //                     'X-Requested-With': 'XMLHttpRequest',
    //                 }
    //             });

    //             const data = await response.json();

    //             if (data.success) {
    //                 // Show success step
    //                 showStep(3);

    //                 // Redirect after delay
    //                 setTimeout(() => {
    //                     window.location.href = data.redirect || '{{ route('dashboard') }}';
    //                 }, 2000);
    //             } else {
    //                 showMessage('otp-messages', data.message || '{{ __('Invalid verification code. Please try again.') }}', 'error');
    //                 otpInputs.forEach(input => {
    //                     input.classList.add('error');
    //                     input.value = '';
    //                 });
    //                 document.getElementById('otp-1').focus();
    //             }
    //         } catch (error) {
    //             console.error('Verify OTP Error:', error);
    //             showMessage('otp-messages', '{{ __('Network error. Please check your connection and try again.') }}', 'error');
    //         } finally {
    //             // Reset button state
    //             btn.disabled = false;
    //             btnText.classList.remove('hidden');
    //             spinner.classList.add('hidden');
    //         }
    //     });

    //     // Resend OTP
    //     document.getElementById('resend-otp-btn').addEventListener('click', async function() {
    //         if (this.disabled) return;

    //         hideMessage('otp-messages');

    //         // Prepare form data
    //         const formData = new FormData();
    //         formData.append('_token', document.querySelector('input[name="_token"]').value);
    //         formData.append('phone_country_code', registrationData.phone_country_code);
    //         formData.append('phone', registrationData.phone);

    //         try {
    //             const response = await fetch('{{ route('register.resend-otp') }}', {
    //                 method: 'POST',
    //                 body: formData,
    //                 headers: {
    //                     'X-Requested-With': 'XMLHttpRequest',
    //                 }
    //             });

    //             const data = await response.json();

    //             if (data.success) {
    //                 showMessage('otp-messages', '{{ __('A new verification code has been sent to your phone.') }}', 'success');
    //                 startResendCountdown(60);

    //                 // Clear OTP inputs
    //                 document.querySelectorAll('.otp-digit').forEach(input => {
    //                     input.value = '';
    //                     input.classList.remove('error', 'success');
    //                 });
    //                 document.getElementById('otp-1').focus();
    //             } else {
    //                 showMessage('otp-messages', data.message || '{{ __('Failed to resend verification code. Please try again.') }}', 'error');
    //             }
    //         } catch (error) {
    //             console.error('Resend OTP Error:', error);
    //             showMessage('otp-messages', '{{ __('Network error. Please check your connection and try again.') }}', 'error');
    //         }
    //     });

    //     // Back to basic info
    //     document.getElementById('back-to-basic-btn').addEventListener('click', function() {
    //         showStep(1);
    //         if (resendTimer) {
    //             clearInterval(resendTimer);
    //             resendTimer = null;
    //         }
    //     });

    //     // Initialize first step
    //     showStep(1);
    // }
</script>
