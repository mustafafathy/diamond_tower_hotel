<?php

namespace App\Http\Controllers;

use App\Models\SecondarySlider;
use Illuminate\Http\Request;

class SecondarySliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SecondarySlider::orderBy('order')
            ->select('id', 'image')
            ->get()
            ->map(function ($item) {
                $item->image = asset('storage/' . $item->image);
                return $item;
            });
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
    public function show(SecondarySlider $secondarySlider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SecondarySlider $secondarySlider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SecondarySlider $secondarySlider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SecondarySlider $secondarySlider)
    {
        //
    }
}
