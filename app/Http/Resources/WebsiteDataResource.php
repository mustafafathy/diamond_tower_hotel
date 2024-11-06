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
            'rooms_count' => $this->when($this->projects_count, $this->projects_count),
            'phone_num1' => $this->when($this->units_count, $this->units_count),
            'phone_num2' => $this->when($this->res_num1, $this->res_num1),
            'email' => $this->when($this->res_num2, $this->res_num2),
            'address' => $this->when($this->whats_app_num, $this->whats_app_num),
            'latitude' => $this->when($this->instagram_link, $this->instagram_link),
            'longitude' => $this->when($this->email, $this->email),
            'instagram_link' => $this->when($this->footer_num1, $this->footer_num1),
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
