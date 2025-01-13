<x-filament-widgets::widget>
    <x-filament::section>
        <div class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">
            Rooms
        </div>
        <div class="flex space-x-6 gap-6">
            <!-- Total Rooms -->
            <x-filament::widget class="flex-1">
                <x-filament::card class="transition-all transform hover:scale-105 hover:shadow-xl bg-gradient-to-r from-blue-500 to-blue-400 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <x-heroicon-o-home-modern class="w-6 h-6" />
                            <span class="text-sm font-medium">Total Rooms</span>
                        </div>
                        <span class="text-3xl font-semibold">{{ $totalRooms }}</span>
                    </div>
                </x-filament::card>
            </x-filament::widget>

            <!-- Available Rooms -->
            <x-filament::widget class="flex-1">
                <x-filament::card class="transition-all transform hover:scale-105 hover:shadow-xl bg-gradient-to-r from-green-500 to-green-400 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <x-heroicon-o-check-circle class="w-6 h-6" />
                            <span class="text-sm font-medium">Available Rooms</span>
                        </div>
                        <span class="text-3xl font-semibold">{{ $availableRooms }}</span>
                    </div>
                </x-filament::card>
            </x-filament::widget>

            <!-- UnAvailable Rooms -->
            <x-filament::widget class="flex-1">
                <x-filament::card class="transition-all transform hover:scale-105 hover:shadow-xl bg-gradient-to-r from-indigo-500 to-indigo-400 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <x-heroicon-o-no-symbol class="w-6 h-6" />
                            <span class="text-sm font-medium">Un-Available Rooms</span>
                        </div>
                        <span class="text-3xl font-semibold">{{ $unAvailableRooms }}</span>
                    </div>
                </x-filament::card>
            </x-filament::widget>

            <!-- Reserved Rooms -->
            <x-filament::widget class="flex-1">
                <x-filament::card class="transition-all transform hover:scale-105 hover:shadow-xl bg-gradient-to-r from-indigo-500 to-indigo-400 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <x-heroicon-o-bookmark class="w-6 h-6" />
                            <span class="text-sm font-medium">Reserved Rooms</span>
                        </div>
                        <span class="text-3xl font-semibold">{{ $reservedRooms }}</span>
                    </div>
                </x-filament::card>
            </x-filament::widget>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>