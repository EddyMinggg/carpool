<!-- 確認 Modal 組件 -->
<div x-data="{ 
    show: false, 
    title: '', 
    message: '', 
    confirmText: '{{ __("Confirm") }}',
    cancelText: '{{ __("Cancel") }}',
    confirmAction: null,
    init() {
        window.showConfirmModal = (options) => {
            this.title = options.title || '{{ __("Confirm Action") }}';
            this.message = options.message || '{{ __("Are you sure?") }}';
            this.confirmText = options.confirmText || '{{ __("Confirm") }}';
            this.cancelText = options.cancelText || '{{ __("Cancel") }}';
            this.confirmAction = options.onConfirm || (() => {});
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
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="title"></h3>
                    <button @click="show = false" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 pb-6">
                <div class="text-gray-600 dark:text-gray-300" x-html="message"></div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button @click="show = false"
                        class="w-full sm:w-auto px-4 py-3 sm:py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors"
                        x-text="cancelText">
                </button>
                <button @click="confirmAction && confirmAction(); show = false"
                        class="w-full sm:w-auto px-4 py-3 sm:py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors"
                        x-text="confirmText">
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>