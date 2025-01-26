<?php

namespace App\Filament\Resources\AvailableRoomResource\Pages;

use App\Filament\Resources\AvailableRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAvailableRooms extends ListRecords
{
    protected static string $resource = AvailableRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
