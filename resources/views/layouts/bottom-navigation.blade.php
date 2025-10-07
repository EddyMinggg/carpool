<div id="bottom-nav" class="fixed bottom-0 left-0 z-50 w-full h-20 md:h-16 bg-primary dark:bg-primary-dark">
    <div class="{{ auth()->user() ? 'grid grid-cols-4' : 'grid grid-cols-2' }} h-full w-full mx-auto font-medium">
        @if (auth()->user() && auth()->user()->isDriver())
            <!-- Driver Navigation -->
            <button type="button"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-200 dark:hover:bg-primary-darkest group"
                onclick="location.href='{{ route('driver.dashboard') }}'">
                <div class="text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">
                    <i class="material-icons text-2xl">&#xe88a;</i>
                </div>
                <span
                    class="text-sm text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">{{ __('Home') }}</span>
            </button>
            <button type="button"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-200 dark:hover:bg-primary-darkest group"
                onclick="location.href='{{ route('driver.my-trips') }}'">
                <div class="text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">
                    <i class="material-icons text-2xl">&#xe8b0;</i>
                </div>
                <span
                    class="text-sm text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">{{ __('My Trips') }}</span>
            </button>
        @else
            <!-- User Navigation -->
            <button type="button"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-200 dark:hover:bg-primary-darkest group"
                onclick="location.href='{{ route('dashboard') }}'">
                <div class="text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">
                    <i class="material-icons text-2xl">&#xe88a;</i>
                </div>
                <span
                    class="text-sm text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">{{ __('Home') }}</span>
            </button>
        @endif
        @if (auth()->user())
            <button type="button"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-200 dark:hover:bg-primary-darkest group"
                onclick="location.href='{{ route('trips') }}'">
                <div class="text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">
                    <i class="material-icons text-2xl">&#xe8b0;</i>
                </div>
                <span
                    class="text-sm text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">{{ __('Order') }}</span>
            </button>
            <button type="button"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-200 dark:hover:bg-primary-darkest group"
                onclick="location.href='{{ route('profile.edit') }}'">
                <div class="text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">
                    <i class="material-icons text-2xl">&#xe7fd;</i>
                </div>
                <span
                    class="text-sm text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">{{ __('Profile') }}</span>
            </button>
            <button type="button"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-200 dark:hover:bg-primary-darkest group"
                onclick="document.getElementById('logout-form').submit();">
                <div class="text-gray-200 dark:text-gray-300 group-hover:text-red-600">
                    <i class="material-icons text-2xl">&#xe879;</i>
                </div>
                <span
                    class="text-sm text-gray-200 dark:text-gray-300 group-hover:text-red-600">{{ __('Logout') }}</span>
            </button>
        @else
            <button type="button"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-200 dark:hover:bg-primary-darkest group"
                onclick="location.href='{{ route('login') }}'">
                <div class="text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">
                    <i class="material-icons text-2xl">&#xe7fd;</i>
                </div>
                <span
                    class="text-sm text-gray-200 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-primary-dark">{{ __('Sign In') }}</span>
            </button>
        @endif
    </div>
</div>

<!-- 隱藏的登出表單 -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
