<?php

namespace App\Filament\Resources\SecondarySliderResource\Pages;

use App\Filament\Resources\SecondarySliderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSecondarySliders extends ListRecords
{
    protected static string $resource = SecondarySliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
