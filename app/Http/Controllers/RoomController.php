<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Reservation;
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
            'children',
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
    public function show($id, $lang = 'ar', Request $request)
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
                'errors' => $validator->errors(),
            ], 422);
        }

        $lang = $lang === 'en' ? 'en' : 'ar';

        $cols = [
            'rooms.id',
            'rooms.name_' . $lang,
            'rooms.description_' . $lang,
            'rooms.space',
            'rooms.allowed_persons',
            'rooms.availability',
            'rooms.start_date',
            'rooms.end_date',
            'rooms.view',
            'rooms.bathroom',
            'rooms.kitchen',
            'rooms.tv',
            'rooms.air_condition',
            'rooms.wifi',
            'rooms.smoke',
            'rooms.disabled',
            'rooms.king_bed',
            'rooms.single_bed',
            'rooms.sofa_bed',
            'rooms.bathroom_details_' . $lang,
            'rooms.kitchen_details_' . $lang,
            'rooms.preparations_' . $lang,
            'rooms.media_tech_' . $lang,
            'rooms.image',
            'rooms.alt_images',
            'rooms.night_price',
            'rooms.discount_price',
        ];

        $checkInDate = Carbon::parse($request->checkInDate);
        $checkOutDate = Carbon::parse($request->checkOutDate);
        $nights = $checkInDate->diffInDays($checkOutDate)  + 2;

        // Handle promo code validation
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
                    'message' => 'Invalid or expired promo code.',
                ], 400);
            }
        }

        $room = Room::where('rooms.id', $id)
            ->join('available_rooms', 'rooms.id', '=', 'available_rooms.room_id')
            ->whereBetween('available_rooms.date', [$checkInDate->format('Y-m-d'), $checkOutDate->format('Y-m-d')])
            ->where('available_rooms.available', '>=', $request->rooms) // Ensure enough rooms are available
            ->whereColumn('available_rooms.available', '>', 'available_rooms.booked') // Ensure enough rooms are available
            ->where('rooms.allowed_persons', '>=', $request->adults + $request->children)
            ->groupBy($cols)
            ->havingRaw('COUNT(DISTINCT available_rooms.date) = ?', [$nights]) // Ensure availability for the full stay
            ->select($cols)
            ->selectRaw('SUM(available_rooms.price) as total_price')->first();

        if (!$room) {
            return response()->json([
                'status' => 'error',
                'message' => 'Room not available for the selected dates or does not exist.',
            ], 404);
        }

        // Prepare additional data
        $room->nights = $nights;
        $room->adults = $request->adults;
        $room->children = $request->children;
        $room->promoCode = $request->promoCode;
        $room->checkInDate = $request->checkInDate;
        $room->checkOutDate = $request->checkOutDate;
        $room->discounted_price = $promo
            ? ($promo->type === 'percentage'
                ? $room->night_price - ($room->night_price * $promo->value / 100)
                : max($room->night_price - $promo->value, 0))
            : null;

        // Pass the data to RoomResource
        $additionalParams = [
            'checkInDate' => $request->checkInDate,
            'checkOutDate' => $request->checkOutDate,
            'nights' => $nights,
            'adults' => $request->adults,
            'children' => $request->children,
            'promoCode' => $request->promoCode,
        ];

        return ['count' => 1, 'data' => [new RoomResource($room, $additionalParams)]];
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
            'rooms.id',
            'rooms.name_' . $lang,
            'rooms.description_' . $lang,
            'rooms.space',
            'rooms.allowed_persons',
            'rooms.view',
            'rooms.bathroom',
            'rooms.kitchen',
            'rooms.tv',
            'rooms.air_condition',
            'rooms.wifi',
            'rooms.smoke',
            'rooms.disabled',
            'rooms.king_bed',
            'rooms.single_bed',
            'rooms.sofa_bed',
            'rooms.bathroom_details_' . $lang,
            'rooms.kitchen_details_' . $lang,
            'rooms.preparations_' . $lang,
            'rooms.media_tech_' . $lang,
            'rooms.image',
            'rooms.night_price',
            'rooms.alt_images'
        ];

        $checkInDate = Carbon::parse($request->checkInDate);
        $checkOutDate = Carbon::parse($request->checkOutDate);
        $nights = $checkInDate->diffInDays($checkOutDate) + 2;

        // Handle promo code validation
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

        // Query rooms with available dates
        $availableRoomsQuery = Room::join('available_rooms', 'rooms.id', '=', 'available_rooms.room_id')
            // ->whereBetween('available_rooms.date', [$checkInDate, $checkOutDate])
            ->whereBetween('available_rooms.date', [$checkInDate->format('Y-m-d'), $checkOutDate->format('Y-m-d')])
            ->where('available_rooms.available', '>=', $request->rooms) // Ensure enough rooms are available
            ->where('rooms.allowed_persons', '>=', $request->adults + $request->children)
            ->groupBy(
                'rooms.id',
                'rooms.name_' . $lang,
                'rooms.description_' . $lang,
                'rooms.space',
                'rooms.allowed_persons',
                'rooms.view',
                'rooms.bathroom',
                'rooms.kitchen',
                'rooms.tv',
                'rooms.air_condition',
                'rooms.wifi',
                'rooms.smoke',
                'rooms.disabled',
                'rooms.king_bed',
                'rooms.single_bed',
                'rooms.sofa_bed',
                'rooms.bathroom_details_' . $lang,
                'rooms.kitchen_details_' . $lang,
                'rooms.preparations_' . $lang,
                'rooms.media_tech_' . $lang,
                'rooms.image',
                'rooms.alt_images',
                'rooms.night_price',

            )
            // ->havingRaw('COUNT(DISTINCT available_rooms.date) = ?', [$nights]) // Ensure availability for the full stay
            ->select($cols)
            ->selectRaw('SUM(available_rooms.price) as total_price');

        // Apply promo code discount
        if ($promo) {
            if ($promo->type === 'percentage') {
                $availableRoomsQuery->selectRaw('SUM(available_rooms.price) - (SUM(available_rooms.price) * ? / 100) as discounted_price', [$promo->value]);
            } elseif ($promo->type === 'fixed') {
                $availableRoomsQuery->selectRaw('GREATEST(SUM(available_rooms.price) - ?, 0) as discounted_price', [$promo->value]);
            }
        }

        // Fetch available rooms
        $availableRooms = $availableRoomsQuery->get();
        // Attach additional info
        $availableRooms->each(function ($room) use ($nights, $promo, $request) {
            $room->nights = $nights;
            $room->adults = $request->adults;
            $room->children = $request->children;
            $room->promoCode = $request->promoCode;

            if ($promo) {
                $room->discounted_price = $promo->type === 'percentage'
                    ? $room->total_price - ($room->total_price * $promo->value / 100)
                    : max($room->total_price - $promo->value, 0);
            }
        });

        return new RoomCollection(RoomResource::collection($availableRooms));
    }

    public function calculateRoomPrices($roomId, $checkInDate, $checkOutDate, $promoCode = null)
    {
        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);
        $nights = $checkIn->diffInDays($checkOut);

        // Fetch room availability and prices for the selected dates
        $roomPrices = DB::table('available_rooms')
            ->where('room_id', $roomId)
            ->whereBetween('date', [$checkInDate, $checkOutDate])
            ->select(DB::raw('SUM(price) as total_price'))
            ->first();

        if (!$roomPrices || $roomPrices->total_price === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Room not available for the selected dates.',
            ], 404);
        }

        $originalPrice = $roomPrices->total_price;
        $finalPrice = $originalPrice;

        // Apply promo code discount if available
        if ($promoCode) {
            $promo = DB::table('coupons')
                ->where('code', $promoCode)
                ->where('is_active', true)
                ->where('from', '<=', $checkInDate)
                ->where('untill', '>=', $checkOutDate)
                ->first();

            if ($promo) {
                if ($promo->type === 'percentage') {
                    $finalPrice -= ($finalPrice * $promo->value / 100);
                } elseif ($promo->type === 'fixed') {
                    $finalPrice = max($finalPrice - $promo->value, 0);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'original_price' => round($originalPrice, 2),
            'final_price' => round($finalPrice, 2),
            'nights' => $nights,
        ]);
    }

    public function confirmReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'promoCode' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $checkInDate = Carbon::parse($request->start_date);
        $checkOutDate = Carbon::parse($request->end_date);
        $nights = $checkInDate->diffInDays($checkOutDate) + 2;

        // ** Fetch room availability strictly from 'available_rooms' **
        $roomAvailability = DB::table('available_rooms')
            ->where('room_id', $request->room_id)
            ->whereBetween('date', [$checkInDate->format('Y-m-d'), $checkOutDate->format('Y-m-d')])
            ->select('date', 'price', 'available', 'booked')
            ->get();

        if ($roomAvailability->count() !== $nights) {
            return response()->json([
                'status' => 'error',
                'message' => 'Room is not available for all selected dates.'
            ], 400);
        }

        // ** Prevent overbooking **
        foreach ($roomAvailability as $day) {
            if ($day->booked >= $day->available) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Not enough available rooms on ' . $day->date
                ], 400);
            }
        }

        // ** Calculate total price from available_rooms **
        $totalPrice = $roomAvailability->sum('price');

        // ** Check for a valid promo code **
        $discount = 0;
        $promo = null;
        if ($request->promoCode) {
            $promo = DB::table('coupons')
                ->where('code', $request->promoCode)
                ->where('is_active', true)
                ->where('from', '<=', $checkInDate)
                ->where('until', '>=', $checkOutDate)
                ->first();

            if ($promo) {
                if ($promo->type === 'percentage') {
                    $discount = $totalPrice * ($promo->value / 100);
                } elseif ($promo->type === 'fixed') {
                    $discount = min($promo->value, $totalPrice);
                }
            }
        }

        // ** Calculate final total price **
        $finalPrice = max($totalPrice - $discount, 0);

        // ** Create reservation with strict data integrity **
        $reservation = Reservation::create([
            'room_id' => $request->room_id,
            'user_id' => $request->user_id,
            'start_date' => $checkInDate,
            'end_date' => $checkOutDate,
            'total_price' => $finalPrice,
            'discount' => $discount,
            'tax' => 0, // Add tax logic if needed
            'gov_tax' => 0, // Add gov tax logic if needed
            'number_of_guests' => $request->adults + $request->children,
            // 'persons' => $request->adults,
            'is_confirmed' => true,
            'is_cancelled' => false,
            'coupon_id' => $promo->id ?? null,
            // 'first_name' => $request->first_name,
            // 'last_name' => $request->last_name,
            // 'guest_name' => $request->guest_name ?? null,
            // 'email' => $request->email,
            // 'country' => $request->country,
            // 'phone' => $request->phone,
            // 'same_person' => $request->same_person,
            // 'for_work' => $request->for_work,
            // 'need_parking' => $request->need_parking,
            'notes' => $request->notes,
            // 'arrival_time' => $request->arrival_time
        ]);

        // ** Update the 'booked' count in 'available_rooms' to reflect the reservation **
        foreach ($roomAvailability as $day) {
            DB::table('available_rooms')
                ->where('room_id', $request->room_id)
                ->where('date', $day->date)
                ->increment('booked');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation confirmed successfully!',
            'reservation' => $reservation
        ], 201);
    }


    // public function confirmReservation(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'room_id' => 'required|exists:rooms,id',
    //         'user_id' => 'required|exists:users,id',
    //         'start_date' => 'required|date|after_or_equal:today',
    //         'end_date' => 'required|date|after:start_date',
    //         'number_of_guests' => 'required|integer|min:1',
    //         'promoCode' => 'nullable|string|max:20'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
    //     }

    //     $checkInDate = Carbon::parse($request->start_date);
    //     $checkOutDate = Carbon::parse($request->end_date);
    //     $nights = $checkInDate->diffInDays($checkOutDate);

    //     // Check room availability
    //     $availableRooms = DB::table('available_rooms')
    //         ->where('room_id', $request->room_id)
    //         ->whereBetween('date', [$checkInDate->format('Y-m-d'), $checkOutDate->format('Y-m-d')])
    //         ->whereColumn('available', '>', 'booked')
    //         ->count();

    //     if ($availableRooms < $nights) {
    //         return response()->json(['status' => 'error', 'message' => 'Room not available for the selected dates.'], 400);
    //     }

    //     // Get room pricing
    //     $room = Room::find($request->room_id);
    //     $totalPrice = $room->night_price * $nights;
    //     // dd($room, $totalPrice);

    //     // Apply promo code discount
    //     if ($request->promoCode) {
    //         $promo = DB::table('coupons')->where('code', $request->promoCode)->where('is_active', true)->first();
    //         if ($promo) {
    //             $totalPrice -= ($promo->type === 'percentage') ? ($totalPrice * $promo->value / 100) : min($promo->value, $totalPrice);
    //         }
    //     }

    //     // Calculate tax and government tax
    //     $tax = $totalPrice * 0.05; // 5% tax
    //     $govTax = $totalPrice * 0.02; // 2% government tax
    //     $finalPrice = $totalPrice + $tax + $govTax;

    //     // Create reservation record
    //     $reservation = Reservation::create([
    //         'room_id' => $request->room_id,
    //         'user_id' => $request->user_id,
    //         'start_date' => $checkInDate,
    //         'end_date' => $checkOutDate,
    //         'total_price' => $finalPrice,
    //         'discount' => $totalPrice - $room->night_price * $nights,
    //         'tax' => $tax,
    //         'gov_tax' => $govTax,
    //         'number_of_guests' => $request->number_of_guests,
    //         'is_confirmed' => false, // Confirmation after payment
    //         'is_cancelled' => false,
    //         'coupon_id' => $promo->id ?? null,
    //         'created_at' => now(),
    //         'updated_at' => now()
    //     ]);

    //     // Update available rooms (increment booked count)
    //     DB::table('available_rooms')
    //         ->where('room_id', $request->room_id)
    //         ->whereBetween('date', [$checkInDate->format('Y-m-d'), $checkOutDate->format('Y-m-d')])
    //         ->increment('booked');

    //     return response()->json(['status' => 'success', 'message' => 'Reservation created successfully.', 'reservation_id' => $reservation->id]);
    // }

    // public function confirmReservation(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'room_id' => 'required|exists:rooms,id',
    //         'user_id' => 'required|exists:users,id',
    //         'start_date' => 'required|date|after_or_equal:today',
    //         'end_date' => 'required|date|after:start_date',
    //         'adults' => 'required|integer|min:1',
    //         'children' => 'nullable|integer|min:0',
    //         'promoCode' => 'nullable|string|max:20'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $checkInDate = Carbon::parse($request->start_date);
    //     $checkOutDate = Carbon::parse($request->end_date);
    //     $nights = $checkInDate->diffInDays($checkOutDate) + 2;

    //     // Fetch available room prices from 'available_rooms'
    //     $roomPrices = DB::table('available_rooms')
    //         ->where('room_id', $request->room_id)
    //         ->whereBetween('date', [$checkInDate->format('Y-m-d'), $checkOutDate->format('Y-m-d')])
    //         ->pluck('price');

    //     if ($roomPrices->count() !== $nights) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Room is not available for all selected dates.'
    //         ], 400);
    //     }

    //     // Calculate total price from 'available_rooms' table
    //     $totalPrice = $roomPrices->sum();

    //     // Apply promo code if valid
    //     $discount = 0;
    //     if ($request->promoCode) {
    //         $promo = DB::table('coupons')
    //             ->where('code', $request->promoCode)
    //             ->where('is_active', true)
    //             ->where('from', '<=', $checkInDate)
    //             ->where('until', '>=', $checkOutDate)
    //             ->first();

    //         if ($promo) {
    //             if ($promo->type === 'percentage') {
    //                 $discount = $totalPrice * ($promo->value / 100);
    //             } elseif ($promo->type === 'fixed') {
    //                 $discount = min($promo->value, $totalPrice);
    //             }
    //         }
    //     }

    //     // Calculate final price after discount
    //     $finalPrice = max($totalPrice - $discount, 0);

    //     // Save the reservation
    //     $reservation = Reservation::create([
    //         'room_id' => $request->room_id,
    //         'user_id' => $request->user_id,
    //         'start_date' => $checkInDate,
    //         'end_date' => $checkOutDate,
    //         'total_price' => $finalPrice,
    //         'discount' => $discount,
    //         'tax' => 0, // Add tax logic if needed
    //         'gov_tax' => 0, // Add gov tax logic if needed
    //         'number_of_guests' => $request->adults + $request->children,
    //         // 'persons' => $request->adults,
    //         'is_confirmed' => true,
    //         'is_cancelled' => false,
    //         'coupon_id' => $promo->id ?? null,
    //         // 'first_name' => $request->first_name,
    //         // 'last_name' => $request->last_name,
    //         // 'guest_name' => $request->guest_name ?? null,
    //         // 'email' => $request->email,
    //         // 'country' => $request->country,
    //         // 'phone' => $request->phone,
    //         // 'same_person' => $request->same_person,
    //         // 'for_work' => $request->for_work,
    //         // 'need_parking' => $request->need_parking,
    //         'notes' => $request->notes,
    //         // 'arrival_time' => $request->arrival_time
    //     ]);

    //     // Update available rooms (increment booked count)
    //     DB::table('available_rooms')
    //         ->where('room_id', $request->room_id)
    //         ->whereBetween('date', [$checkInDate->format('Y-m-d'), $checkOutDate->format('Y-m-d')])
    //         ->increment('booked');

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Reservation confirmed successfully!',
    //         'reservation' => $reservation
    //     ], 201);
    // }
}
