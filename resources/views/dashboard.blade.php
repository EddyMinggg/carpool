<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="" method="post">
                <div class="md:flex gap-4">
                    <div class="w-full mt-2">
                        <x-input-label for="pickup" :value="__('Pickup Location')" />
                        <x-text-input id="pickup" class="block mt-2 w-full" type="text" name="pickup" :value="old('pickup')" required />
                        <x-input-error :messages="$errors->get('pickup')" class="mt-2" />
                    </div>

                    <div class="w-full mt-2">
                        <x-input-label for="dropoff" :value="__('Dropoff Location')" />
                        <x-text-input id="dropoff" class="block mt-2 w-full" type="text" name="dropoff" :value="old('dropoff')" required />
                        <x-input-error :messages="$errors->get('dropoff')" class="mt-2" />
                    </div>

                    <div class="w-full flex gap-4 mt-2">
                        <div class="w-full">
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" class="block mt-2 w-full" type="date" name="date" :value="old('date')" lang="en-US" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>
                        <div class="w-full">
                            <x-input-label for="time" :value="__('Time')" />
                            <x-text-input id="time" class="block mt-2 w-full" type="time" name="time" :value="old('time')" required />
                            <x-input-error :messages="$errors->get('time')" class="mt-2" />
                        </div>
                    </div>

                    <div class="w-full flex pt-8">
                        <x-primary-button class="w-full" style="display: block !important; font-size: 14px;">
                            {{ __('Check Price') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
</x-app-layout>
