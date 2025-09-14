<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    @php
        $dates = collect($groupedTrips->keys())->sort()->take(3);
    @endphp
    @foreach($dates as $date)
        <div class="max-w-7xl mx-auto px-3 pt-8 sm:px-6 lg:px-8 w-full relative overflow-x-auto">
            <h2 class="pb-4 font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
                {{ $date }}
            </h2>
            <div class="rounded-lg overflow-auto shadow-md w-full">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">{{ __('Departure Time') }}</th>
                            <th class="px-6 py-3">{{ __('Destination') }}</th>
                            <th class="px-6 py-3">{{ __('Is New Carpool') }}</th>
                            <th class="px-6 py-3">{{ __('Remaining Time') }}</th>
                            <th class="px-6 py-3">{{ __('Current People') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedTrips[$date] as $trip)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-blue-100 dark:hover:bg-gray-600 cursor-pointer" onclick="window.location='{{ route('trips.show', $trip->id) }}'">
                                <td class="px-6 py-4">{{ $trip->formatted_departure_time }}</td>
                                <td class="px-6 py-4">{{ $trip->dropoff_location ?? __('Huafa') }}</td>
                                <td class="px-6 py-4">
                                    @if($trip->current_people === 0)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-blue-100 text-blue-800">{{ __('Is New Carpool') }}</span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-gray-100 text-gray-800">{{ __('Already Joined') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $trip->remaining_time ? $trip->remaining_time : __('Not Started') }}</td>
                                <td class="px-6 py-4">{{ $trip->current_people }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
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
