<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'id',
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
            'id',
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
            'id',
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

        $promo = null;
        if ($request->promoCode) {
            $promo = DB::table('coupons')
            ->where('code', $request->promoCode)
            ->where('is_active', true)
            ->where('from', '<=', $checkInDate)
            ->where('untill', '>=', $checkOutDate)
            ->first();

            if (!$promo) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or expired promo code.'
                ], 400);
            }
        }

        $availableRoomsQuery = Room::whereDoesntHave('reservations', function ($query) use ($checkInDate, $checkOutDate) {
            $query->where(function ($query) use ($checkInDate, $checkOutDate) {
                $query->where('start_date', '<', $checkOutDate)
                    ->where('end_date', '>', $checkInDate);
            });
        });

        $availableRoomsQuery->where('allowed_persons', '>=', $request->adults + $request->children);


        // Apply promo code discount
        if ($promo) {
            if ($promo->type === 'percentage') {
                $availableRoomsQuery->select($cols)
                    ->selectRaw('night_price - (night_price * ? / 100) as discounted_price', [$promo->value]);
            } elseif ($promo->type === 'fixed') {
                $availableRoomsQuery->select($cols)
                    ->selectRaw('GREATEST(night_price - ?, 0) as discounted_price', [$promo->value]);
            }
        } else {
            $availableRoomsQuery->select($cols);
        }

        // Fetch the available rooms
        $availableRooms = $availableRoomsQuery->get();

        // Attach the number of nights and discounted prices
        $availableRooms->each(function ($room) use ($nights, $promo) {
            $room->nights = $nights;
            if ($promo) {
                $room->discounted_price = $promo->type === 'percentage'
                ? $room->night_price - ($room->night_price * $promo->value / 100)
                    : max($room->night_price - $promo->value, 0);
            }
        });

        return new RoomCollection(new RoomResource($availableRooms));
    }
}
