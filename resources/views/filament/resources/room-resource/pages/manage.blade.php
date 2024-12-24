<x-filament::page>
    <form wire:submit.prevent="saveAvailabilityAndPrices">
        <div class="space-y-6">
            {{-- Header --}}
            <div class="text-lg font-bold text-gray-800 dark:text-gray-200">
                Manage Availability and Prices
            </div>

            {{-- List of Rooms --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($availability as $index => $room)
                <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                    {{-- Room Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-gray-700 dark:text-gray-200">
                            {{ $room['name'] }}
                        </div>
                    </div>


                    {{-- Availability Input --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Available Rooms
                        </label>
                        <x-filament::input
                            type="number"
                            wire:model.defer="availability.{{ $index }}.availability"
                            min="0"
                            class="mt-1 dark:bg-gray-700 dark:text-gray-200" />
                    </div>

                    {{-- Price Input --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nightly Price ($)
                        </label>
                        <x-filament::input
                            type="number"
                            wire:model.defer="availability.{{ $index }}.night_price"
                            min="0"
                            step="0.01"
                            class="mt-1 dark:bg-gray-700 dark:text-gray-200" />
                    </div>

                    {{-- Tooltip --}}
                    <div class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                        Adjust the nightly and discounted prices for this room.
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Save Button --}}
            <div class="text-right">
                <x-filament::button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white dark:bg-primary-600 dark:hover:bg-primary-700">
                    Save Changes
                </x-filament::button>
            </div>
        </div>
    </form>
</x-filament::page>