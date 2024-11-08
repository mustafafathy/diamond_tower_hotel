<?php

namespace App\Http\Controllers;

use App\Models\SecondarySlider;
use App\Models\Slider;
use App\Models\WebsiteData;
use Illuminate\Http\Request;

class WebsiteDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($lang = 'ar')
    {
        $lang = $lang == 'en' ? 'en' : 'ar';

        $cols = [
            'rooms_count',
            'phone_num1',
            'phone_num2',
            'email',
            'address_'.$lang,
            'latitude',
            'longitude',
            'instagram_link',
        ];

        return WebsiteData::select($cols)->first();
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
    public function show(WebsiteData $websiteData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WebsiteData $websiteData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebsiteData $websiteData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebsiteData $websiteData)
    {
        //
    }

    public function slider()
    {
        return Slider::orderBy('order')
            ->select('id', 'image')
            ->get()
            ->map(function ($item) {
                $item->image = asset('storage/' . $item->image);
                return $item;
            });
    }  

    public function secondarySlider()
    {
        return SecondarySlider::orderBy('order')
            ->select('id', 'image')
            ->get()
            ->map(function ($item) {
                $item->image = asset('storage/' . $item->image);
                return $item;
            });
    } 
}
