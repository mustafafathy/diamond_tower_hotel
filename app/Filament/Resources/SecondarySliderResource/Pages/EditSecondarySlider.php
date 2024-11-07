<?php

namespace App\Filament\Resources\SecondarySliderResource\Pages;

use App\Filament\Resources\SecondarySliderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSecondarySlider extends EditRecord
{
    protected static string $resource = SecondarySliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
