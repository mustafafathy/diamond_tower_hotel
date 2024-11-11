<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($lang = 'ar')
    {
        $lang = $lang == 'en' ? 'en' : 'ar';

        $cols = [
            'name_' . $lang,
            'description_' . $lang,
            'space',
            'allowed_persons',
            'availability',
            'view',
            'bathroom',
            'kitchen',
            'tv',
            'air_condition',
            'wifi',
            'smoke',
            'disabled',
            'king_bed',
            'single_bed',
            'sofa_bed',
            'bathroom_details_' . $lang,
            'kitchen_details_' . $lang,
            'preparations_' . $lang,
            'media_tech_' . $lang,
            'image',
            'alt_images'
        ];

        return new RoomCollection(new RoomResource(Room::select($cols)->get()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id, $lang = 'ar')
    {
        $lang = $lang == 'en' ? 'en' : 'ar';

        $cols = [
            'name_' . $lang,
            'description_' . $lang,
            'space',
            'allowed_persons',
            'availability',
            'view',
            'bathroom',
            'kitchen',
            'tv',
            'air_condition',
            'wifi',
            'smoke',
            'disabled',
            'king_bed',
            'single_bed',
            'sofa_bed',
            'bathroom_details_' . $lang,
            'kitchen_details_' . $lang,
            'preparations_' . $lang,
            'media_tech_' . $lang,
            'image',
            'alt_images'
        ];

        return new RoomCollection(new RoomResource(Room::where('id', $id)->select($cols)->get()));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //
    }

    public function getAvailableRooms(Request $request, $lang = 'ar')
    {
        $validator = Validator::make($request->all(), [
            'checkInDate' => 'required|date|after_or_equal:today',
            'checkOutDate' => 'required|date|after:checkInDate',
            'rooms' => 'required|integer|min:1',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'childAges' => 'array',
            'childAges.*' => 'integer|min:0|max:17',
            'promoCode' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $lang = $lang == 'en' ? 'en' : 'ar';

        $cols = [
            'name_' . $lang,
            'description_' . $lang,
            'space',
            'allowed_persons',
            'availability',
            'view',
            'bathroom',
            'kitchen',
            'tv',
            'air_condition',
            'wifi',
            'smoke',
            'disabled',
            'king_bed',
            'single_bed',
            'sofa_bed',
            'bathroom_details_' . $lang,
            'kitchen_details_' . $lang,
            'preparations_' . $lang,
            'media_tech_' . $lang,
            'image',
            'alt_images',
            'night_price',
            'discount_price'
        ];

        $checkInDate = Carbon::parse($request->checkInDate);
        $checkOutDate = Carbon::parse($request->checkOutDate);

        $nights = $checkInDate->diffInDays($checkOutDate);

        // dd($nights);

        $availableRoomsQuery = Room::whereDoesntHave('reservations', function ($query) use ($checkInDate, $checkOutDate) {
            $query->where(function ($query) use ($checkInDate, $checkOutDate) {
                $query->where('start_date', '<', $checkOutDate)
                    ->where('end_date', '>', $checkInDate);
            });
        });

        $availableRooms = $availableRoomsQuery->where('allowed_persons', '>=', $request->adults + $request->children)
            ->select($cols)
            ->get();

        $availableRooms->each(function ($room) use ($nights) {
            $room->nights = $nights;
        });

        return new RoomCollection(new RoomResource($availableRooms));
    }
}
