<?php

namespace App\Filament\Resources\ContactResource\Widgets;

use App\Models\Contact;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class ContactWidget extends Widget
{
    protected static string $view = 'filament.resources.contact-resource.widgets.contact-widget';
    protected int | string | array $columnSpan = 'full';


    protected function getViewData(): array
    {
        return [
            'totalContacts' => Contact::count(),
            'unreadMessages' => Contact::where('is_read', false)->count(),
            'contactsToday' => Contact::whereDate('created_at', Carbon::today())->count(),
            'contactsThisMonth' => Contact::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count(),
        ];
    }
}
