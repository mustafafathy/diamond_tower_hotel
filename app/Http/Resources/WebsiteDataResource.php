<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'rooms_count' => $this->when($this->rooms_count, $this->rooms_count),
            'phone_num1' => $this->when($this->phone_num1, $this->phone_num1),
            'phone_num2' => $this->when($this->phone_num2, $this->phone_num2),
            'email' => $this->when($this->email, $this->email),
            'address' => $this->when($this->address, $this->address),
            'latitude' => $this->when($this->latitude, $this->latitude),
            'longitude' => $this->when($this->longitude, $this->longitude),
            'instagram_link' => $this->when($this->instagram_link, $this->instagram_link),
            'image_1' => $this->when($this->image_1, $this->image_1),
            'image_2' => $this->when($this->image_2, $this->image_2),
            'image_3' => $this->when($this->image_3, $this->image_3),
            'image_4' => $this->when($this->image_4, $this->image_4),
        ];
    }

    protected function generateLogosUrls()
    {
        $logosUrls = [];
        foreach ($this->logos as $logo) {
            $logosUrls[] = asset('storage/' . $logo);
        }
        return $logosUrls;
    }
}
