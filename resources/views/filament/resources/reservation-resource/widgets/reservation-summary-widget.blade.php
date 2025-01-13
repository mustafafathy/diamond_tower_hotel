<x-filament-widgets::widget>
    <x-filament::section>
        <div>
            <div class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">
                Reservations
            </div>

            <div class="grid grid-cols-1 gap-0 md:grid-cols-3 lg:grid-cols-3">
                <!-- Total Reservations -->
                <div class="p-4 rounded-lg shadow bg-blue-100 text-blue-800 dark:bg-primary-600 dark:text-white">
                    <div class="text-sm font-medium opacity-90">Total Reservations</div>
                    <div class="mt-1 text-2xl font-bold">{{ $totalReservations }}</div>
                </div>

                <!-- Confirmed Reservations -->
                <div class="p-4 rounded-lg shadow bg-green-100 text-green-800 dark:bg-success-600 dark:text-white">
                    <div class="text-sm font-medium opacity-90">Confirmed Reservations</div>
                    <div class="mt-1 text-2xl font-bold">{{ $confirmedReservations }}</div>
                </div>

                <!-- Cancelled Reservations -->
                <div class="p-4 rounded-lg shadow bg-red-100 text-red-800 dark:bg-danger-600 dark:text-white">
                    <div class="text-sm font-medium opacity-90">Cancelled Reservations</div>
                    <div class="mt-1 text-2xl font-bold">{{ $cancelledReservations }}</div>
                </div>

                <!-- Reservations Today -->
                <div class="p-4 rounded-lg shadow bg-blue-200 text-blue-900 dark:bg-info-600 dark:text-white">
                    <div class="text-sm font-medium opacity-90">Reservations Today</div>
                    <div class="mt-1 text-2xl font-bold">{{ $reservationsToday }}</div>
                </div>

                <!-- Active Reservations -->
                <div class="p-4 rounded-lg shadow bg-yellow-100 text-yellow-800 dark:bg-warning-600 dark:text-white">
                    <div class="text-sm font-medium opacity-90">Active Reservations</div>
                    <div class="mt-1 text-2xl font-bold">{{ $activeReservations }}</div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>