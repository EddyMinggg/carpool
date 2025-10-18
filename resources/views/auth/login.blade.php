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

            <a href="#" class="tab-link block font-medium text-sm text-gray-700 dark:text-gray-300 tab-login inline-block py-2 px-4 hover:text-stone-500 transition-colors duration-300 mr-1" data-dui-tab-target="tab1-group4">
                {{ __('Login') }}
            </a>
            <a href="#" class="tab-link block font-medium text-sm text-gray-700 dark:text-gray-300 tab-join-trip inline-block py-2 px-4 hover:text-stone-500 transition-colors duration-300 mr-1" data-dui-tab-target="tab2-group4">
                {{ __('Join Trip') }}
            </a>
            <a href="#" class="tab-link block font-medium text-sm text-gray-700 dark:text-gray-300 tab-register inline-block py-2 px-4  hover:text-stone-500 transition-colors duration-300 mr-1" data-dui-tab-target="tab3-group4">
                {{ __('Register') }}
            </a>
        </div>
        <div class="mt-4 tab-content-container">
            <div id="tab1-group4" class="tab-content hidden font-medium text-sm text-gray-700 dark:text-gray-300">
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
            <div id="tab2-group4" class="tab-content hidden font-medium text-sm text-gray-700 dark:text-gray-300">
                <form method="POST" action="{{ route('guest') }}">
                    @csrf
                    <!-- Invitation Code -->
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
            <div id="tab3-group4" class="tab-content hidden font-medium text-sm text-gray-700 dark:text-gray-300">

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

                        <!-- Display All Validation Errors -->
                        @if ($errors->any())
                            <div class="mb-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                                <div class="font-medium mb-2">{{ __('Whoops! Something went wrong.') }}</div>
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Name -->
                        <div>
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                                :value="old('username')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            <div id="username-error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email_register" :value="__('Email')" />
                            <x-text-input id="email_register" class="block mt-1 w-full" type="email"
                                name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <div id="password-error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
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

        // Define error conditions for each tab more clearly
        const errors = @json($errors->toArray());
        const oldInput = {
            username: @json(old('username')),
            email: @json(old('email')),
            phone: @json(old('phone')),
            phone_country_code: @json(old('phone_country_code')),
            invitation_code: @json(old('invitation_code')),
            phone_invite: @json(old('phone_invite')),
            phone_country_code_invite: @json(old('phone_country_code_invite')),
            password: @json(old('password'))
        };

        // Debug: Log errors and old input
        console.log('Errors:', errors);
        console.log('Old Input:', oldInput);

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

        // Determine which tab to show based on form submission context
        function determineActiveTab() {
            // Priority 1: Check if register form was submitted (look for register-specific fields)
            // Register form has: username, email, phone, phone_country_code, password, password_confirmation
            if (oldInput.username || oldInput.phone || oldInput.phone_country_code) {
                console.log('Detected: Register form (old input)');
                return { tabId: 'tab3-group4', linkClass: '.tab-register' };
            }
            
            // Check for register-specific errors
            if (errors.username || errors.phone || errors.phone_country_code || errors.password_confirmation) {
                console.log('Detected: Register form (errors)');
                return { tabId: 'tab3-group4', linkClass: '.tab-register' };
            }
            
            // Priority 2: Check if join trip form was submitted
            if (oldInput.invitation_code || oldInput.phone_invite || oldInput.phone_country_code_invite) {
                console.log('Detected: Join Trip form (old input)');
                return { tabId: 'tab2-group4', linkClass: '.tab-join-trip' };
            }
            
            if (errors.invitation_code || errors.phone_invite || errors.phone_country_code_invite) {
                console.log('Detected: Join Trip form (errors)');
                return { tabId: 'tab2-group4', linkClass: '.tab-join-trip' };
            }
            
            // Priority 3: Check if login form was submitted or has login errors
            // Login only has email and password (no username, no phone)
            if (errors.email || errors.password) {
                console.log('Detected: Login form (errors)');
                return { tabId: 'tab1-group4', linkClass: '.tab-login' };
            }
            
            // Default to login tab
            console.log('Default: Login tab');
            return { tabId: 'tab1-group4', linkClass: '.tab-login' };
        }

        // Initialize the correct tab
        const activeTab = determineActiveTab();
        const activeLink = document.querySelector(activeTab.linkClass);
        if (activeLink) {
            showTab(activeTab.tabId, activeLink);
        }

        // Add click handlers to tab links
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-dui-tab-target');
                showTab(targetId, this);
            });
        });
    });
</script>
