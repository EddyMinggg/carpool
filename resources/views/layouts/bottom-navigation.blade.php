<div id="bottom-nav" class="fixed bottom-0 left-0 z-50 w-full h-20 md:h-16 bg-white border-t border-gray-200 dark:bg-gray-700 dark:border-gray-600">
    <div class="grid h-full w-full grid-cols-4 mx-auto font-medium">
        <button type="button" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group" onclick="location.href='{{ route('dashboard') }}'">
            <div class="text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">
                <i class="material-icons text-2xl">&#xe88a;</i>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">Home</span>
        </button>
        <button type="button" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group" onclick="location.href='{{ route('trips') }}'">
            <div class="text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">
                <i class="material-icons text-2xl">&#xe8b0;</i>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">Order</span>
        </button>
        <button type="button" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group" onclick="location.href='{{ route('profile.edit') }}'">
            <div class="text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">
                <i class="material-icons text-2xl">&#xe7fd;</i>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">Profile</span>
        </button>
        <button type="button" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group" onclick="document.getElementById('logout-form').submit();">
            <div class="text-gray-500 dark:text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-500">
                <i class="material-icons text-2xl">&#xe879;</i>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-500">Logout</span>
        </button>
    </div>
</div>

<!-- 隱藏的登出表單 -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>