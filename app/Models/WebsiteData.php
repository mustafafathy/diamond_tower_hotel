<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WebsiteData extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        self::deleting(function (WebsiteData $row) {

            Storage::disk('public')->delete($row->image_1);
            Storage::disk('public')->delete($row->image_2);
            Storage::disk('public')->delete($row->image_3);
            Storage::disk('public')->delete($row->image_4);
        });

        self::updated(function (WebsiteData $row) {

            if ($row->isDirty('image_1')) {
                $oldImage = $row->getOriginal('image_1');
                Storage::disk('public')->delete($oldImage);
            }

            if ($row->isDirty('image_2')) {
                $oldImage = $row->getOriginal('image_2');
                Storage::disk('public')->delete($oldImage);
            }

            if ($row->isDirty('image_3')) {
                $oldImage = $row->getOriginal('image_3');
                Storage::disk('public')->delete($oldImage);
            }

            if ($row->isDirty('image_4')) {
                $oldImage = $row->getOriginal('image_4');
                Storage::disk('public')->delete($oldImage);
            }
        });
    }

}
