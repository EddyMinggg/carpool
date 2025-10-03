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
        <div class="flex border-b border-stone-200 dark:border-stone-700 relative" role="tablist">
            <div
                class="absolute bottom-0 h-0.5 bg-gray-600 dark:bg-gray-200 transition-transform duration-300 transform scale-x-0 translate-x-0 tab-indicator">
            </div>

            <a href="#"
                class="tab-link block font-medium text-sm text-gray-700 dark:text-gray-300 {{ $errors->any() && (old('username') || old('email') || old('phone')) ? '' : 'active' }} inline-block py-2 px-4 hover:text-stone-500 transition-colors duration-300 mr-1"
                data-dui-tab-target="tab1-group4">
                {{ __('Login') }}
            </a>
            <a href="#"
                class="tab-link block font-medium text-sm text-gray-700 dark:text-gray-300 {{ $errors->any() && ( old('invitation_code') || old('phone')) ? '' : 'active' }} inline-block py-2 px-4 hover:text-stone-500 transition-colors duration-300 mr-1"
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
            <div id="tab1-group4" class="tab-content {{ $errors->any() && (old('username') || old('email') || old('phone')) ? 'hidden' : 'block' }} font-medium text-sm text-gray-700 dark:text-gray-300">
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

                        <x-text-input id="password_login" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
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
            <div id="tab2-group4" class="tab-content {{ $errors->any() && ( old('invitation_code') || old('phone')) ? 'hidden' : 'block' }} font-medium text-sm text-gray-700 dark:text-gray-300">
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
                                <option value="+852" {{ old('phone_country_code', '+852') == '+852' ? 'selected' : '' }}>+852 (HK)</option>
                                <option value="+86" {{ old('phone_country_code') == '+86' ? 'selected' : '' }}>+86 (CN)</option>
                            </select>
                            <x-text-input id="phone_invite" class="block w-full rounded-l-none border-l-0" 
                                type="tel" 
                                name="phone_invite" 
                                :value="old('phone_invite')" 
                                required 
                                autocomplete="tel"
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
            <div id="tab3-group4" class="tab-content {{ $errors->any() && (old('username') || old('email') || old('phone')) ? 'block' : 'hidden' }} font-medium text-sm text-gray-700 dark:text-gray-300">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="username" :value="__('Username')" />
                        <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                            :value="old('username')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email_register" :value="__('Email')" />
                        <x-text-input id="email_register" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Phone Number -->
                    <div class="mt-4">
                        <x-input-label for="phone_register" :value="__('Phone Number')" />
                        <div class="flex mt-1">
                            <select id="phone_country_code" name="phone_country_code" 
                                class="rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                <option value="+852" {{ old('phone_country_code', '+852') == '+852' ? 'selected' : '' }}>+852 (HK)</option>
                                <option value="+86" {{ old('phone_country_code') == '+86' ? 'selected' : '' }}>+86 (CN)</option>
                            </select>
                            <x-text-input id="phone_register" class="block w-full rounded-l-none border-l-0" 
                                type="tel" 
                                name="phone" 
                                :value="old('phone')" 
                                required 
                                autocomplete="tel"
                                placeholder="12345678" />
                        </div>
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        <x-input-error :messages="$errors->get('phone_country_code')" class="mt-2" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ __('We will send you an OTP to verify your phone number') }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password_register" :value="__('Password')" />

                        <x-text-input id="password_register" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                </form>
                
                <!-- Simple OTP Test (logs OTP code) -->
                @if (app()->environment('local'))
                    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-xs text-blue-700 dark:text-blue-300 mb-2">
                            <strong>Debug Mode:</strong> Test OTP without AWS (OTP will be logged)
                        </p>
                        <form method="POST" action="{{ route('simple.register') }}">
                            @csrf
                            <button type="submit" 
                                    class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded"
                                    onclick="this.form.elements.namedItem('username').value = document.getElementById('username').value; 
                                             this.form.elements.namedItem('email').value = document.getElementById('email_register').value;
                                             this.form.elements.namedItem('phone_country_code').value = document.getElementById('phone_country_code').value;
                                             this.form.elements.namedItem('phone').value = document.getElementById('phone_register').value;
                                             this.form.elements.namedItem('password').value = document.getElementById('password_register').value;
                                             this.form.elements.namedItem('password_confirmation').value = document.getElementById('password_confirmation').value;">
                                Test with Simple OTP
                            </button>
                            <input type="hidden" name="username">
                            <input type="hidden" name="email">
                            <input type="hidden" name="phone_country_code">
                            <input type="hidden" name="phone">
                            <input type="hidden" name="password">
                            <input type="hidden" name="password_confirmation">
                        </form>
                    </div>
                @endif
                

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
    });
</script>
