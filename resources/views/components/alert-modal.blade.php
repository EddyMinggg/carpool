<!-- 提示 Modal 組件 -->
<div x-data="{ 
    show: false, 
    title: '', 
    message: '', 
    type: 'info',
    buttonText: '{{ __("OK") }}',
    init() {
        window.showAlertModal = (options) => {
            this.title = options.title || '{{ __("Notice") }}';
            this.message = options.message || '';
            this.type = options.type || 'info';
            this.buttonText = options.buttonText || '{{ __("OK") }}';
            this.show = true;
        };
    }
}" 
x-show="show" 
x-cloak
class="fixed inset-0 z-50 overflow-y-auto"
x-transition:enter="ease-out duration-300"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0">

    <!-- 背景遮罩 -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
         @click="show = false"></div>

    <!-- Modal 內容 -->
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all w-full max-w-lg mx-4 sm:mx-auto"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Modal Header -->
            <div class="px-6 pt-6 pb-4">
                <div class="flex items-center">
                    <!-- 圖標 -->
                    <div class="flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full"
                         :class="{
                            'bg-red-100 dark:bg-red-900': type === 'error',
                            'bg-yellow-100 dark:bg-yellow-900': type === 'warning',
                            'bg-blue-100 dark:bg-blue-900': type === 'info',
                            'bg-green-100 dark:bg-green-900': type === 'success'
                         }">
                        <!-- Error Icon -->
                        <svg x-show="type === 'error'" class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.982 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <!-- Warning Icon -->
                        <svg x-show="type === 'warning'" class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.982 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <!-- Info Icon -->
                        <svg x-show="type === 'info'" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <!-- Success Icon -->
                        <svg x-show="type === 'success'" class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    
                    <!-- 標題 -->
                    <div class="ml-4 text-left">
                        <h3 class="text-lg font-semibold" 
                            :class="{
                                'text-red-900 dark:text-red-100': type === 'error',
                                'text-yellow-900 dark:text-yellow-100': type === 'warning',
                                'text-blue-900 dark:text-blue-100': type === 'info',
                                'text-green-900 dark:text-green-100': type === 'success'
                            }"
                            x-text="title">
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 pb-6">
                <div class="mt-2">
                    <p class="text-gray-600 dark:text-gray-300" x-html="message"></p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4">
                <button @click="show = false"
                        class="w-full px-4 py-3 text-sm font-medium text-white rounded-lg transition-colors"
                        :class="{
                            'bg-red-600 hover:bg-red-700': type === 'error',
                            'bg-yellow-600 hover:bg-yellow-700': type === 'warning',
                            'bg-blue-600 hover:bg-blue-700': type === 'info',
                            'bg-green-600 hover:bg-green-700': type === 'success'
                        }"
                        x-text="buttonText">
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>