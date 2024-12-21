<?php

namespace App\Filament\Resources\RoomResource\Widgets;

use App\Models\Room;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\View\View;

class RoomSummaryWidget extends Widget
{
    protected static string $view = 'filament.resources.room-resource.widgets.room-summary-widget';

    public function render() : view
    {
        // Total rooms
        $totalRooms = Room::count();

        // Available rooms
        $availableRooms = Room::where('availability', 1)->count();

        // Rooms with specific features
        $roomsWithView = Room::where('view', true)->count();
        $roomsWithBathroom = Room::where('bathroom', true)->count();
        $roomsWithKitchen = Room::where('kitchen', true)->count();

        return view('filament.resources.room-resource.widgets.room-summary-widget', compact(
            'totalRooms',
            'availableRooms',
            'roomsWithView',
            'roomsWithBathroom',
        ));
    }
}
