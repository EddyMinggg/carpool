<x-app-layout>
    @section('Title', __('Contact Us'))
    
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Contact Us') }}</h1>
            <p class="text-xl text-gray-600 dark:text-gray-400">{{ __('We\'re here to help you 24/7') }}</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <!-- Combined Contact Section -->
            <div class="bg-secondary dark:bg-secondary-accent rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Get In Touch') }}</h2>
                
                <!-- Quick Contact Buttons -->
                <div class="grid md:grid-cols-3 gap-4 mb-8">
                    <a href="https://wa.me/85252414992" target="_blank"
                        class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-semibold transition">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span class="hidden sm:inline">{{ __('Contact via WhatsApp') }}</span>
                        <span class="sm:hidden">WhatsApp</span>
                    </a>

                    <a href="tel:+85252414992"
                        class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-semibold transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="hidden sm:inline">{{ __('Call Us Now') }}</span>
                        <span class="sm:hidden">{{ __('Phone') }}</span>
                    </a>

                    <a href="mailto:danielwu@snowpins.com"
                        class="flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white py-3 px-4 rounded-lg font-semibold transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="hidden sm:inline">{{ __('Send Email') }}</span>
                        <span class="sm:hidden">{{ __('Email') }}</span>
                    </a>
                </div>

                <p class="text-sm text-gray-500 dark:text-gray-500 text-center mb-8">
                    {{ __('We typically respond within 24 hours') }}
                </p>

                <!-- Contact Details -->
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/30 rounded-lg p-3">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Phone') }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">+852 5241 4992</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500">{{ __('24/7 Customer Service') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Email') }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">danielwu@snowpins.com</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/30 rounded-lg p-3">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('WhatsApp') }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">+852 5241 4992</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/30 rounded-lg p-3">
                            <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Office Address') }}</h3>
                            @if(app()->getLocale() == 'en')
                                <p class="text-gray-600 dark:text-gray-400">
                                    Room 03, 11/F, SOLO<br>
                                    83 Bic Fat Road<br>
                                    Tai Kok Tsui<br>
                                    Kowloon, Hong Kong SAR
                                </p>
                            @else
                                <p class="text-gray-600 dark:text-gray-400 mt-1">
                                    香港九龍大角咀<br>
                                    必發道83號<br>
                                    SOLO 11樓03室
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Operating Hours -->
                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Operating Hours') }}</h3>
                    <div class="grid md:grid-cols-2 gap-4 text-gray-600 dark:text-gray-400">
                        <div class="flex justify-between">
                            <span>{{ __('Customer Service') }}:</span>
                            <span class="font-semibold">{{ __('24/7') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('Office Hours') }}:</span>
                            <span class="font-semibold">{{ __('Mon-Fri 9:00-18:00') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
