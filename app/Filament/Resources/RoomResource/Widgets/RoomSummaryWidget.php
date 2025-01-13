<?php

namespace App\Filament\Resources\RoomResource\Widgets;

use App\Models\Room;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\View\View;

class RoomSummaryWidget extends Widget
{
    protected static string $view = 'filament.resources.room-resource.widgets.room-summary-widget';
    protected int | string | array $columnSpan = 'full';

    public function render(): view
    {
        // Total rooms
        $totalRooms = Room::sum('availability');

        // Available rooms
        $availableRooms = Room::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->sum('availability');

        $reservedRooms = Room::where('availability', 1)
            ->whereHas('reservations', function ($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })
            ->count();

        $unAvailableRooms = $totalRooms - $availableRooms - $reservedRooms;


        return view('filament.resources.room-resource.widgets.room-summary-widget', compact(
            'totalRooms',
            'availableRooms',
            'reservedRooms',
            'unAvailableRooms',
        ));
    }
}
