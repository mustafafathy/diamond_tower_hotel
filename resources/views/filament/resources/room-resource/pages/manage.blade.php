<x-filament::page>
    <form wire:submit.prevent="saveAvailabilityAndPrices" class="space-y-8">
        {{-- Header --}}
        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
            Manage Availability and Prices
        </div>

        {{-- Room Cards --}}
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($availability as $index => $room)
            <div class="p-6 bg-white border border-gray-300 rounded-lg shadow-md hover:shadow-lg dark:bg-gray-800 dark:border-gray-600">
                {{-- Room Title --}}
                <div class="mb-4 text-lg font-bold text-gray-800 dark:text-gray-200">
                    {{ $room['name'] }}
                </div>

                {{-- Availability Input --}}
                <div class="mb-4">
                    <label for="availability-{{ $index }}" class="block text-sm font-semibold text-gray-700 dark:text-gray-400">
                        Available Rooms
                    </label>
                    <x-filament::input
                        id="availability-{{ $index }}"
                        type="number"
                        wire:model.defer="availability.{{ $index }}.availability"
                        min="0"
                        class="w-full px-4 py-3 mt-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black-500 focus:border-black-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-black-400 dark:focus:border-black-400 transition-colors duration-300 ease-in-out" />
                </div>

                {{-- Nightly Price Input --}}
                <div class="mb-4">
                    <label for="night-price-{{ $index }}" class="block text-sm font-semibold text-gray-700 dark:text-gray-400">
                        Nightly Price ($)
                    </label>
                    <x-filament::input
                        id="night-price-{{ $index }}"
                        type="number"
                        wire:model.defer="availability.{{ $index }}.night_price"
                        min="0"
                        step="0.01"
                        class="w-full px-4 py-3 mt-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black-500 focus:border-black-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-black-400 dark:focus:border-black-400 transition-colors duration-300 ease-in-out" />
                </div>

                {{-- Discount Price Input --}}
                <div class="mb-4">
                    <label for="discount-price-{{ $index }}" class="block text-sm font-semibold text-gray-700 dark:text-gray-400">
                        Member Price ($)
                    </label>
                    <x-filament::input
                        id="discount-price-{{ $index }}"
                        type="number"
                        wire:model.defer="availability.{{ $index }}.discount_price"
                        min="0"
                        step="0.01"
                        class="w-full px-4 py-3 mt-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black-500 focus:border-black-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-black-400 dark:focus:border-black-400 transition-colors duration-300 ease-in-out" />
                </div>

                {{-- Date Range Inputs --}}
                <div class="flex gap-4">
                    {{-- Start Date Input --}}
                    <div class="flex-1">
                        <label for="start-date-{{ $index }}" class="block text-sm font-semibold text-gray-700 dark:text-gray-400">
                            Start Date
                        </label>
                        <x-filament::input
                            id="start-date-{{ $index }}"
                            type="date"
                            wire:model.defer="availability.{{ $index }}.start_date"
                            class="w-full px-4 py-3 mt-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black-500 focus:border-black-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-black-400 dark:focus:border-black-400 transition-colors duration-300 ease-in-out" />
                    </div>

                    {{-- End Date Input --}}
                    <div class="flex-1">
                        <label for="end-date-{{ $index }}" class="block text-sm font-semibold text-gray-700 dark:text-gray-400">
                            End Date
                        </label>
                        <x-filament::input
                            id="end-date-{{ $index }}"
                            type="date"
                            wire:model.defer="availability.{{ $index }}.end_date"
                            class="w-full px-4 py-3 mt-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black-500 focus:border-black-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-black-400 dark:focus:border-black-400 transition-colors duration-300 ease-in-out" />
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end">
            <x-filament::button type="submit" class="px-6 py-3 text-sm font-semibold text-white bg-black-600 rounded-lg hover:bg-black-700 focus:outline-none focus:ring-2 focus:ring-black-500 focus:ring-offset-2 dark:bg-black-500 dark:hover:bg-black-600">
                Save Changes
            </x-filament::button>
        </div>
    </form>
</x-filament::page>