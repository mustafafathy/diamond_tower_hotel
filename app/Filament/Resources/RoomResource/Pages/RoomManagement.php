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
                'description' => $item->description_en, // Add your additional fields here
                'availability' => $item->availability,
            ];
        })->toArray();
    }

    public function saveAvailability()
    {
        // Validate inputs
        Validator::make($this->availability, [
            '*.id' => 'required|exists:rooms,id', // Ensure the ID exists
            '*.availability' => 'required|numeric|min:0', // Ensure availability is numeric and non-negative
        ])->validate();

        // Update availability in the database
        foreach ($this->availability as $room) {
            Room::where('id', $room['id'])->update([
                'availability' => $room['availability'],
            ]);
        }


        Notification::make()
            ->title('Availability updated successfully!')
            ->success()
            ->send(); 
    }
}
