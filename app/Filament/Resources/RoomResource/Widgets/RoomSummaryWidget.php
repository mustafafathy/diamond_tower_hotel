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
        $totalRooms = Room::count();

        // Available rooms
        $availableRooms = Room::where('availability', 1)
            ->whereDoesntHave('reservations', function ($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })
            ->count();
            
        $unAvailableRooms = Room::where('availability', 1)
            ->whereHas('reservations', function ($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })
            ->count();


        return view('filament.resources.room-resource.widgets.room-summary-widget', compact(
            'totalRooms',
            'availableRooms',
            'unAvailableRooms',
        ));
    }
}
