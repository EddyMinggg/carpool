@section('Title', 'Trips')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Trip History') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 w-full relative overflow-x-auto">
        <div class="flex items-center md:justify-end space-y-4 md:space-y-0 py-4">
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="table-search-users"
                    class="block w-full md:w-64 pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search for orders"/>
            </div>
        </div>
        <div class="rounded-lg overflow-auto shadow-md w-full">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Pickup Location') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Dropoff Location') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Departure Time') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Fee') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Type') }}
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ __('Status') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">
                                {{ $payment->trip->pickup_location }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $payment->trip->dropoff_location }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $payment->trip->planned_departure_time }}
                            </td>
                            <td class="px-6 py-4">
                                {{ '$' . $payment->amount }}
                            </td>
                            <td class="px-6 py-4">
                                {{ ucfirst($payment->type) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg {{ $payment->paid == 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $payment->paid == 0 ? __('Unpaid') : __('Paid') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500">
                                No order history
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

<style>
    .table {
        border-radius: 0.5rem;
    }
</style>
