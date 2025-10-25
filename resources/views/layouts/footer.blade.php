<!-- Footer -->
<footer class="bg-secondary dark:bg-secondary-accent">
    <div class="max-w-7xl mx-auto p-8">
        <!-- Desktop Footer -->
        {{-- <div class="hidden md:grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Company Info -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ config('app.name') }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                    {{ __('Safe, Fast, Reliable') }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    {{ __('Cross-Border Transportation Services') }}
                </p>
                <div class="mt-4 text-xs text-gray-500 dark:text-gray-500">
                    <p>© {{ date('Y') }} {{ config('app.name') }}</p>
                    <p>{{ __('All rights reserved.') }}</p>
                </div>
            </div>

            <!-- About & Links -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('About') }}
                </h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('about.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                            {{ __('About Us') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about.contact') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                            {{ __('Contact Us') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about.privacy') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                            {{ __('Privacy Policy') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about.terms') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                            {{ __('Terms of Service') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('Contact') }}
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-phone text-blue-600 dark:text-blue-400 mt-1"></i>
                        <a href="tel:+85298588879" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                            +852 9858 8879
                        </a>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-envelope text-blue-600 dark:text-blue-400 mt-1"></i>
                        <a href="mailto:danielwu@snowpins.com" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition break-all">
                            danielwu@snowpins.com
                        </a>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fab fa-whatsapp text-green-600 dark:text-green-400 mt-1"></i>
                        <a href="https://wa.me/85298588879" target="_blank" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition">
                            WhatsApp
                        </a>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-map-marker-alt text-red-600 dark:text-red-400 mt-1"></i>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            @if(app()->getLocale() == 'en')
                                Room 03, 11/F, SOLO<br>
                                83 Bic Fat Road<br>
                                Tai Kok Tsui<br>
                                Kowloon, Hong Kong SAR
                            @else
                                香港九龍大角咀<br>
                                必發道83號<br>
                                SOLO 11樓03室
                            @endif
                        </span>
                    </li>
                </ul>
            </div>
        </div> --}}

        <!-- Mobile Footer -->
        <div class="space-y-6">
            <!-- About Links -->
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">
                    {{ __('About') }}
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('about.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('About Us') }}
                    </a>
                    <a href="{{ route('about.contact') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Contact Us') }}
                    </a>
                    <a href="{{ route('about.privacy') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Privacy Policy') }}
                    </a>
                    <a href="{{ route('about.terms') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Terms of Service') }}
                    </a>
                </div>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">
                    {{ __('Contact') }}
                </h3>
                <div class="space-y-2">
                    <a href="tel:+85298588879" class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                        <i class="fas fa-phone text-blue-600"></i>
                        <span>+852 9858 8879</span>
                    </a>
                    <a href="mailto:danielwu@snowpins.com" class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                        <i class="fas fa-envelope text-blue-600"></i>
                        <span>danielwu@snowpins.com</span>
                    </a>
                    <a href="https://wa.me/85252414992" target="_blank" class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400">
                        <i class="fab fa-whatsapp text-base text-green-600"></i>
                        <span>WhatsApp</span>
                    </a>
                </div>
            </div>

            <!-- Copyright -->
            <div class="text-center pt-4 border-t border-neutral-200 dark:border-neutral-600">
                <p class="text-xs text-gray-500 dark:text-gray-500">
                    © {{ date('Y') }} {{ config('app.name') }}<br>
                    {{ __('All rights reserved.') }}
                </p>
            </div>
        </div>
    </div>
</footer>
