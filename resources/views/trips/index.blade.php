<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Trips') }}
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

    <table class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($trips as $trip)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->creator->username ?? 'Unknown User' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->pickup_location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->dropoff_location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->planned_departure_time->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->max_people }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $trip->trip_status === 'pending' ? 'bg-blue-100 text-blue-800' : 
                                           ($trip->trip_status === 'voting' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($trip->trip_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst($trip->trip_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.trips.show', $trip->id) }}" class="text-blue-600 hover:text-blue-900 mr-3 transition-colors">View</a>
                                    <a href="{{ route('admin.trips.edit', $trip->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3 transition-colors">Edit</a>
                                    <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" onclick="return confirm('Are you sure you want to delete this?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500">
                                    No trip data available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
</x-app-layout>