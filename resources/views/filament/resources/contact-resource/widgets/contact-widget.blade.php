<x-filament-widgets::widget>
    <x-filament::section>
        <div class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">
            Contacts
        </div>
        <div class="flex space-x-6 gap-6">
            <!-- Total Contacts -->
            <x-filament::widget class="flex-1">
                <x-filament::card class="transition-all transform hover:scale-105 hover:shadow-xl bg-gradient-to-r from-blue-500 to-blue-400 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <x-heroicon-o-user class="w-6 h-6" />
                            <span class="text-sm font-medium">Total Contacts</span>
                        </div>
                        <span class="text-3xl font-semibold">{{ $totalContacts }}</span>
                    </div>
                </x-filament::card>
            </x-filament::widget>

            <!-- Unread Messages -->
            <x-filament::widget class="flex-1">
                <x-filament::card class="transition-all transform hover:scale-105 hover:shadow-xl bg-gradient-to-r from-green-500 to-green-400 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <x-heroicon-o-bell-slash class="w-6 h-6" />
                            <span class="text-sm font-medium">Unread Messages</span>
                        </div>
                        <span class="text-3xl font-semibold">{{ $unreadMessages }}</span>
                    </div>
                </x-filament::card>
            </x-filament::widget>

            <!-- Contacts Added Today -->
            <x-filament::widget class="flex-1">
                <x-filament::card class="transition-all transform hover:scale-105 hover:shadow-xl bg-gradient-to-r from-indigo-500 to-indigo-400 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <x-heroicon-o-user-plus class="w-6 h-6" />
                            <span class="text-sm font-medium">Contacts Added Today</span>
                        </div>
                        <span class="text-3xl font-semibold">{{ $contactsToday }}</span>
                    </div>
                </x-filament::card>
            </x-filament::widget>

            <!-- Contacts Added This Month -->
            <x-filament::widget class="flex-1">
                <x-filament::card class="transition-all transform hover:scale-105 hover:shadow-xl bg-gradient-to-r from-indigo-500 to-indigo-400 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <x-heroicon-o-user-plus class="w-6 h-6" />
                            <span class="text-sm font-medium"> This Month</span>
                        </div>
                        <span class="text-3xl font-semibold">{{ $contactsThisMonth }}</span>
                    </div>
                </x-filament::card>
            </x-filament::widget>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>