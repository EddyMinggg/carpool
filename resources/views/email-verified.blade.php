<x-guest-layout>
    <div class="flex flex-col sm:justify-center items-center bg-gray-100 dark:bg-gray-900">
        <div class="w-full p-8 bg-secondary dark:bg-secondary-accent overflow-hidden">

            <!-- Header -->
            <div class="mb-6 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-primary dark:bg-primary-dark">
                    <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-4">
                    {{ __('Verified') }}
                </h2>
                <p class="font-semibold text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('Your email has been successfully verifed!') }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <!-- Resend Email Button -->
                <form action="{{ route('dashboard') }}">
                    @csrf
                    <x-primary-button class="w-full justify-center mb-4 text-sm">
                        {{ __('Login to Your Account') }}
                    </x-primary-button>
                </form>

            </div>
        </div>
    </div>
</x-guest-layout>
