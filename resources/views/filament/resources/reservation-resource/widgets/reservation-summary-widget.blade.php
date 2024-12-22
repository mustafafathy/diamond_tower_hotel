<div>
    Reservation
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
    <div class="p-4 bg-primary-500 text-white rounded-lg shadow">
        <div class="text-sm font-medium">Total Reservations</div>
        <div class="mt-1 text-2xl font-bold">{{ $totalReservations }}</div>
    </div>
    
    <div class="p-4 bg-success-500 text-white rounded-lg shadow">
        <div class="text-sm font-medium">Confirmed Reservations</div>
        <div class="mt-1 text-2xl font-bold">{{ $confirmedReservations }}</div>
    </div>
    
    <div class="p-4 bg-danger-500 text-white rounded-lg shadow">
        <div class="text-sm font-medium">Cancelled Reservations</div>
        <div class="mt-1 text-2xl font-bold">{{ $cancelledReservations }}</div>
    </div>

    <div class="p-4 bg-info-500 text-white rounded-lg shadow">
        <div class="text-sm font-medium">Reservations Today</div>
        <div class="mt-1 text-2xl font-bold">{{ $reservationsToday }}</div>
    </div>
    
    <div class="p-4 bg-warning-500 text-white rounded-lg shadow">
        <div class="text-sm font-medium">Active Reservations</div>
        <div class="mt-1 text-2xl font-bold">{{ $activeReservations }}</div>
    </div>
</div>

</div>