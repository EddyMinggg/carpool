@section('Title', __('Payment'))

<x-app-layout>
    <x-slot name="header">
        <button onclick="window.location='{{ route('dashboard') }}'" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            <span class="material-icons text-gray-700 dark:text-gray-300">arrow_back</span>
        </button>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 pb-12">
        <div class="p-4">
            <h2 class="text-lg text-gray-900 dark:text-gray-300 font-black">
                {{ __('Make your deposit payment.') }}
            </h2>

            <div class="mt-8 sm:mx-0 text-md text-gray-900 dark:text-gray-300">
                <ul class="list-disc list-inside">
                    <li>
                        {{ __('Scan the QR Code below.') }}
                    </li>
                    <li>
                        <span class="font-normal">
                            {{ __('Pay the required amount: ') }}
                        </span>
                        <span class="font-black underline">
                            {{ '$' . $payment->amount }}
                        </span>
                    </li>
                    <li>
                        <span>
                            {{ __('Enter the reference code as the note of the transaction.') }}
                        </span>
                    </li>
                </ul>
            </div>
            <div class="w-full mt-6">
                <div class="relative">
                    <x-input-label for="reference-copy-button">
                        {{ __('Reference Code') }}
                    </x-input-label>
                    <x-text-input id="reference-copy-button" class="mt-2 w-full p-3.5" value="{{ $payment->reference_code }}"
                        disabled readonly />
                    <button data-copy-to-clipboard-target="reference-copy-button"
                        data-tooltip-target="tooltip-copy-reference-copy-button"
                        class="mt-1 absolute end-2 top-4 translate-y-5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg p-2 inline-flex items-center justify-center">
                        <span id="default-icon">
                            <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 18 20">
                                <path
                                    d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
                            </svg>
                        </span>
                        <span id="success-icon" class="hidden">
                            <svg class="w-3.5 h-3.5 text-blue-700 dark:text-blue-500" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                            </svg>
                        </span>
                    </button>
                    <div id="tooltip-copy-reference-copy-button" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                        <span id="default-tooltip-message">Copy to clipboard</span>
                        <span id="success-tooltip-message" class="hidden">Copied!</span>
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <div class="w-full mt-8 flex justify-center">
                <img class="w-full md:w-96 object-contain" src="{{ asset('img/frame.png') }}" />
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    window.addEventListener('load', function() {
        const clipboard = FlowbiteInstances.getInstance('CopyClipboard', 'reference-copy-button');
        const tooltip = FlowbiteInstances.getInstance('Tooltip', 'tooltip-copy-reference-copy-button');

        const $defaultIcon = document.getElementById('default-icon');
        const $successIcon = document.getElementById('success-icon');

        const $defaultTooltipMessage = document.getElementById('default-tooltip-message');
        const $successTooltipMessage = document.getElementById('success-tooltip-message');

        clipboard.updateOnCopyCallback((clipboard) => {
            showSuccess();

            // reset to default state
            setTimeout(() => {
                resetToDefault();
            }, 2000);
        })

        const showSuccess = () => {
            $defaultIcon.classList.add('hidden');
            $successIcon.classList.remove('hidden');
            $defaultTooltipMessage.classList.add('hidden');
            $successTooltipMessage.classList.remove('hidden');
            tooltip.show();
        }

        const resetToDefault = () => {
            $defaultIcon.classList.remove('hidden');
            $successIcon.classList.add('hidden');
            $defaultTooltipMessage.classList.remove('hidden');
            $successTooltipMessage.classList.add('hidden');
            tooltip.hide();
        }
    })
</script>