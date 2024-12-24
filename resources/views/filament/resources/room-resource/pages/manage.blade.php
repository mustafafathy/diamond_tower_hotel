<x-filament::page>
    <form wire:submit.prevent="saveAvailability">
        <div class="space-y-6">
            {{-- Header --}}
            <div class="text-lg font-bold text-gray-800 dark:text-gray-200">
                Manage Availability
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

                        {{-- Badge for Room Type --}}
                        <span class="px-3 py-1 text-xs font-medium leading-tight text-gray-600 bg-gray-200 rounded-full dark:text-gray-100 dark:bg-gray-700">
                            {{ $room['availability'] }}
                        </span>
                    </div>

                    {{-- Room Details --}}
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                        <div>Price: $150/night</div>
                        <div>Description: {{ $room['description'] }}</div>
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

                    {{-- Tooltip --}}
                    <div class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                        Enter the number of available rooms. Set to 0 to mark as unavailable.
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