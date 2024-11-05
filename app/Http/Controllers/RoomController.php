<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;

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
    public function show(Room $room)
    {
        //
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
}
