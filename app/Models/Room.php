<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PharIo\Manifest\Application;

class Room extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'alt_images' => 'array',
        'bathroom_details_ar' => 'array',
        'bathroom_details_en' => 'array',
        'kitchen_details_ar' => 'array',
        'kitchen_details_en' => 'array',
        'preparations_ar' => 'array',
        'preparations_en' => 'array',
        'media_tech_ar' => 'array',
        'media_tech_en' => 'array',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
