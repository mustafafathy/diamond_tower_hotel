<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        self::deleting(function (Slider $slider) {

            Storage::disk('public')->delete($slider->image);
        });

        self::updated(function (Slider $slider) {

            if ($slider->isDirty('image')) {
                $oldImage = $slider->getOriginal('image');

                Storage::disk('public')->delete($oldImage);
            }
        });
    }

}
