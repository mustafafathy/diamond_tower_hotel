<?php

namespace App\Filament\Resources\WebsiteDataResource\Pages;

use App\Filament\Resources\WebsiteDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteData extends EditRecord
{
    protected static string $resource = WebsiteDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
