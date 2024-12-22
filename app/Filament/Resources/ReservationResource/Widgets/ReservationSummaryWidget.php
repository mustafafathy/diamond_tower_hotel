<?php

namespace App\Filament\Resources\ReservationResource\Widgets;

use App\Models\Reservation;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class ReservationSummaryWidget extends Widget
{
    protected static string $view = 'filament.resources.reservation-resource.widgets.reservation-summary-widget';

    protected function getViewData(): array
    {
        return [
            'totalReservations' => Reservation::count(),
            'confirmedReservations' => Reservation::where('is_confirmed', true)->count(),
            'cancelledReservations' => Reservation::where('is_cancelled', true)->count(),
            'reservationsToday' => Reservation::whereDate('created_at', Carbon::today())->count(),
            'activeReservations' => Reservation::where('is_confirmed', true)
                ->where('is_cancelled', false)
                ->whereDate('end_date', '>=', Carbon::today())
                ->count(),
        ];
    }
}
