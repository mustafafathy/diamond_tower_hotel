<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SecondarySlider extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        self::deleting(function (SecondarySlider $secondarySlider) {

            Storage::disk('public')->delete($secondarySlider->image);
        });

        self::updated(function (SecondarySlider $secondarySlider) {

            if ($secondarySlider->isDirty('image')) {
                $oldImage = $secondarySlider->getOriginal('image');

                Storage::disk('public')->delete($oldImage);
            }
        });
    }
}
