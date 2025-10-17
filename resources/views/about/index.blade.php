<x-app-layout>
    @section('Title', __('About Us'))
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ __('About Snowpins') }}
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                {{ __('Your trusted partner for safe, affordable, and convenient cross-border transportation between Hong Kong and Mainland China.') }}
            </p>
        </div>

        <!-- About Our Service -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">{{ __('About Our Service') }}</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🛡️</div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ __('Safe & Reliable') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('All our drivers are professionally licensed and vehicles are regularly inspected for your safety.') }}</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">💰</div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ __('Affordable Pricing') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('Share rides with other passengers and save money on cross-border transportation costs.') }}</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">⏰</div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ __('24/7 Service') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('Book trips anytime with our flexible scheduling system and real-time availability.') }}</p>
                </div>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Our Mission') }}</h2>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ __('To provide safe, affordable, and convenient cross-border transportation services that connect Hong Kong and Mainland China, making travel easier for everyone. We are committed to delivering exceptional customer service and building a trusted community of travelers.') }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Our Vision') }}</h2>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ __('To become the leading carpool service provider in the Greater Bay Area, connecting communities and promoting sustainable transportation through shared mobility solutions.') }}
                </p>
            </div>
        </div>

        <!-- Operating Hours -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Operating Hours') }}</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="border-l-4 border-blue-600 dark:border-blue-400 pl-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ __('Customer Service') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('Monday - Sunday: 24 hours') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500">{{ __('Phone & WhatsApp support available') }}</p>
                </div>
                <div class="border-l-4 border-purple-600 dark:border-purple-400 pl-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ __('Trip Services') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('Monday - Sunday: 24 hours') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500">{{ __('Golden Hour and Regular trips available') }}</p>
                </div>
            </div>
        </div>

        <!-- Legal & Compliance -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Legal & Compliance') }}</h2>
            <div class="space-y-4">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Licensed Transportation Service') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ __('We operate under full compliance with Hong Kong and Mainland China transportation regulations.') }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Data Protection') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ __('We comply with the Personal Data (Privacy) Ordinance and protect your personal information.') }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Insurance Coverage') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ __('All vehicles are covered by comprehensive insurance for passenger safety.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Notice -->
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-6 rounded-lg mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200">{{ __('Opt-In Communication Policy') }}</h3>
                    <p class="text-yellow-700 dark:text-yellow-300 mt-2">
                        {{ __('We only contact customers who have provided their mobile phone number and explicitly opted-in to receive communications from us via WhatsApp. You can opt-out at any time by sending "STOP" to our WhatsApp Business number.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('More Information') }}</h2>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('about.terms') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('Terms of Service') }}
                </a>
                <a href="{{ route('about.privacy') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    {{ __('Privacy Policy') }}
                </a>
                <a href="{{ route('about.contact') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('Contact Us') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
