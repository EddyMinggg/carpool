<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <div class="flex">
                <select id="phone_country_code" name="phone_country_code" 
                    class="rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                    <option value="+852" {{ old('phone_country_code', '+852') == '+852' ? 'selected' : '' }}>+852 (HK)</option>
                    <option value="+86" {{ old('phone_country_code') == '+86' ? 'selected' : '' }}>+86 (CN)</option>
                    <option value="+1" {{ old('phone_country_code') == '+1' ? 'selected' : '' }}>+1 (US)</option>
                    <option value="+44" {{ old('phone_country_code') == '+44' ? 'selected' : '' }}>+44 (UK)</option>
                </select>
                <x-text-input id="phone" class="block w-full rounded-l-none border-l-0" 
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
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
