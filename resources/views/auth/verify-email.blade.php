<x-guest-layout>
    <div class="flex flex-col sm:justify-center items-center bg-gray-100 dark:bg-gray-900 mt-8">
        <div class="w-full p-8 bg-white dark:bg-gray-800 overflow-hidden">
            
            <!-- Header -->
            <div class="mb-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900/50">
                    <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-4">
                    {{ __('Verify Your Email') }}
                </h2>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('We have sent a verification link to') }}
                </p>
                <p class="font-semibold text-gray-900 dark:text-gray-100 mt-2">
                    {{ auth()->user()->email }}
                </p>
            </div>

            <!-- Success Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg">
                    {{ __('A new verification link has been sent to your email address.') }}
                </div>
            @endif

            <!-- Instructions -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-blue-900 dark:text-blue-100 text-sm">
                            {{ __('Check Your Email') }}
                        </div>
                        <div class="text-blue-700 dark:text-blue-300 text-sm mt-1">
                            {{ __('Click the verification link in the email we sent to complete your registration. Check your spam folder if you don\'t see it.') }}
                        </div>
                    </div>
                </div>
            </div>



            <!-- Verification Status -->
            @if (auth()->user()->hasVerifiedPhone())
                <div class="mb-4 flex items-center gap-2 text-green-600 dark:text-green-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm font-medium">{{ __('Phone Number Verified') }}</span>
                </div>
            @endif

            <div class="mb-6 flex items-center gap-2 text-gray-400 dark:text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium">{{ __('Email Verification Pending') }}</span>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <!-- Resend Email Button -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-primary-button class="w-full justify-center mb-4 text-sm">
                        {{ __('Resend Verification Email') }}
                    </x-primary-button>
                </form>

                <!-- Secondary Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('dashboard') }}" 
                       class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 font-medium">
                        {{ __('Skip for Now') }}
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 font-medium">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
