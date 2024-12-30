<x-filament::page>
    <form wire:submit.prevent="saveAvailabilityAndPrices" class="space-y-8">
        {{-- Header --}}
        <div class="text-2xl font-bold text-gray-900 dark:text-white">
            Manage Availability and Prices
        </div>

        {{-- Room Cards --}}
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($availability as $index => $room)
            <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
                {{-- Room Title --}}
                <div class="mb-4 text-lg font-semibold text-blue-900 dark:text-blue-300">
                    {{ $room['name'] }}
                </div>

                {{-- Availability Input --}}
                <div class="mb-4">
                    <label for="availability-{{ $index }}" class="block text-sm font-medium text-blue-700 dark:text-blue-300">
                        Available Rooms
                    </label>
                    <x-filament::input
                        id="availability-{{ $index }}"
                        type="number"
                        wire:model.defer="availability.{{ $index }}.availability"
                        min="0"
                        class="w-full px-3 py-2 mt-1 text-blue-900 bg-white border border-blue-300 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-blue-100 dark:border-gray-600" />
                </div>

                {{-- Nightly Price Input --}}
                <div class="mb-4">
                    <label for="night-price-{{ $index }}" class="block text-sm font-medium text-blue-700 dark:text-blue-300">
                        Nightly Price ($)
                    </label>
                    <x-filament::input
                        id="night-price-{{ $index }}"
                        type="number"
                        wire:model.defer="availability.{{ $index }}.night_price"
                        min="0"
                        step="0.01"
                        class="w-full px-3 py-2 mt-1 text-blue-900 bg-white border border-blue-300 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-blue-100 dark:border-gray-600" />
                </div>

                {{-- Discount Price Input --}}
                <div class="mb-4">
                    <label for="discount-price-{{ $index }}" class="block text-sm font-medium text-blue-700 dark:text-blue-300">
                        Member Price ($)
                    </label>
                    <x-filament::input
                        id="discount-price-{{ $index }}"
                        type="number"
                        wire:model.defer="availability.{{ $index }}.discount_price"
                        min="0"
                        step="0.01"
                        class="w-full px-3 py-2 mt-1 text-blue-900 bg-white border border-blue-300 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-blue-100 dark:border-gray-600" />
                </div>

                {{-- Date Range Inputs --}}
                <div class="flex gap-4">
                    {{-- Start Date Input --}}
                    <div class="flex-1">
                        <label for="start-date-{{ $index }}" class="block text-sm font-medium text-blue-700 dark:text-blue-300">
                            Start Date
                        </label>
                        <x-filament::input
                            id="start-date-{{ $index }}"
                            type="date"
                            wire:model.defer="availability.{{ $index }}.start_date"
                            class="w-full px-3 py-2 mt-1 text-blue-900 bg-white border border-blue-300 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-blue-100 dark:border-gray-600" />
                    </div>

                    {{-- End Date Input --}}
                    <div class="flex-1">
                        <label for="end-date-{{ $index }}" class="block text-sm font-medium text-blue-700 dark:text-blue-300">
                            End Date
                        </label>
                        <x-filament::input
                            id="end-date-{{ $index }}"
                            type="date"
                            wire:model.defer="availability.{{ $index }}.end_date"
                            class="w-full px-3 py-2 mt-1 text-blue-900 bg-white border border-blue-300 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-blue-100 dark:border-gray-600" />
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end">
            <x-filament::button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                Save Changes
            </x-filament::button>
        </div>
    </form>
</x-filament::page>