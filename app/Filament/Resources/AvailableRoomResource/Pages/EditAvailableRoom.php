<?php

namespace App\Filament\Resources\AvailableRoomResource\Pages;

use App\Filament\Resources\AvailableRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAvailableRoom extends EditRecord
{
    protected static string $resource = AvailableRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
