<?php

namespace App\Filament\Resources\RoomResource\Pages;

use App\Filament\Resources\RoomResource;
use App\Models\Room;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Validator;

class RoomManagement extends ManageRecords
{

    protected static string $resource = RoomResource::class;

    protected static string $view = 'filament.resources.room-resource.pages.manage';


    public $availability = [];

    public function mount(): void
    {
        // Load initial availability data
        $this->availability = Room::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name_en,
                'availability' => $item->availability,
                'night_price' => $item->night_price,
                'discount_price' => $item->discount_price,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
            ];
        })->toArray();
    }

    public function saveAvailabilityAndPrices()
    {
        $this->validate([
            'availability.*.availability' => 'required|integer|min:0',
            'availability.*.night_price' => 'required|numeric|min:0',
            'availability.*.discount_price' => 'required|numeric|min:0',
            'availability.*.start_date' => 'required|date',
            'availability.*.end_date' => 'required|date',
        ]);

        foreach ($this->availability as $roomData) {
            $room = Room::find($roomData['id']); // Replace with your model's query logic

            if ($room) {
                $room->update([
                    'availability' => $roomData['availability'],
                    'night_price' => $roomData['night_price'],
                    'discount_price' => $roomData['discount_price'],
                    'start_date' => $roomData['start_date'],
                    'end_date' => $roomData['end_date'],
                ]);
            }
        }

        Notification::make()
            ->title('Rooms updated successfully!')
            ->success()
            ->send();
    }
}
