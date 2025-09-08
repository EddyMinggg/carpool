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
            <form method="POST" action="{{ route('trips.store') }}">
                @csrf
                <div class="md:flex gap-4">
                    <div class="w-full mt-2">
                        <x-input-label for="pickup_location" :value="__('Pickup Location')" />
                        <x-text-input id="pickup_location" class="block mt-2 w-full" type="text" name="pickup_location"
                            :value="old('pickup_location')" required />
                        <x-input-error :messages="$errors->get('pickup_location')" class="mt-2" />
                    </div>

                    <div class="w-full mt-2">
                        <x-input-label for="dropoff_location" :value="__('Dropoff Location')" />
                        <x-text-input id="dropoff_location" class="block mt-2 w-full" type="text" name="dropoff_location"
                            :value="old('dropoff_location')" required />
                        <x-input-error :messages="$errors->get('dropoff_location')" class="mt-2" />
                    </div>

                    <div class="w-full flex gap-4 mt-2">
                        <div class="w-full">
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" class="block mt-2 w-full" type="date" name="date"
                                :value="old('date')" lang="en-US" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>
                        <div class="w-full">
                            <x-input-label for="time" :value="__('Time')" />
                            <x-text-input id="time" class="block mt-2 w-full" type="time" name="time"
                                :value="old('time')" required />
                            <x-input-error :messages="$errors->get('time')" class="mt-2" />
                        </div>
                    </div>

                    <input type="hidden" id="planned_departure_time" name="planned_departure_time">

                    <div class="w-full flex pt-8">
                        <x-primary-button class="w-full" style="display: block !important; font-size: 14px;"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-create-order')">
                            {{ __('Check Price') }}
                        </x-primary-button>
                    </div>

                    <x-modal name="confirm-create-order" focusable>
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Are you sure you want to create the order?') }}
                            </h2>

                            <div class="flex mt-4">
                                <div class="flex items-center h-5">
                                    <input id="private-checkbox" type="checkbox"
                                        value=""
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                </div>
                                <div class="ms-2 text-sm">
                                    <label for="private-checkbox"
                                        class="font-medium text-gray-900 dark:text-gray-300">Private</label>
                                    <p id="private-checkbox-text"
                                        class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-300">This will make your
                                        trip private, meaning other users cannot join your trip and split the fee.</p>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    {{ __('Cancel') }}
                                </x-secondary-button>

                                <x-primary-button class="ms-3">
                                    {{ __('Confirm') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </x-modal>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script type="module">
    // jQuery document ready function
    $(document).ready(function() {
        // Function to update the hidden input field with the combined date and time
        function updateCombinedDateTime() {
            const combinedDateTime = $('#date').val() + ' ' + $('#time').val();
            $('#planned_departure_time').val(combinedDateTime);
        }

        // Update the combined date and time whenever the date or time input changes
        $('#date, #time').on('input', updateCombinedDateTime);
    });
</script>