<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id'    => $this->when($this->id, $this->id),
            'name_ar'    => $this->when($this->name_ar, $this->name_ar),
            'name_en'    => $this->when($this->name_en, $this->name_en),
            'description_ar'     => $this->when($this->description_ar, $this->description_ar),
            'description_en'     => $this->when($this->description_en, $this->description_en),
            'space'  => $this->when($this->space, $this->space),
            'allowed_persons'    => $this->when($this->allowed_persons, $this->allowed_persons),
            'availability'   => $this->when($this->availability, $this->availability),
            'view'   => $this->view,
            'bathroom'   => $this->bathroom,
            'kitchen'    => $this->kitchen,
            'tv'     => $this->tv,
            'air_condition'  => $this->air_condition,
            'wifi'   => $this->wifi,
            'smoke'  => $this->smoke,
            'disabled'   => $this->disabled,
            'king_bed'   => $this->when($this->king_bed, $this->king_bed),
            'single_bed'     => $this->when($this->single_bed, $this->single_bed),
            'sofa_bed'   => $this->when($this->sofa_bed, $this->sofa_bed),
            'bathroom_details_ar'    => $this->when($this->bathroom_details_ar, $this->bathroom_details_ar),
            'bathroom_details_en'    => $this->when($this->bathroom_details_en, $this->bathroom_details_en),
            'kitchen_details_ar'     => $this->when($this->kitchen_details_ar, $this->kitchen_details_ar),
            'kitchen_details_en'     => $this->when($this->kitchen_details_en, $this->kitchen_details_en),
            'preparations_ar'    => $this->when($this->preparations_ar, $this->preparations_ar),
            'preparations_en'    => $this->when($this->preparations_en, $this->preparations_en),
            'media_tech_ar'  => $this->when($this->media_tech_ar, $this->media_tech_ar),
            'media_tech_en'  => $this->when($this->media_tech_en, $this->media_tech_en),
            'image'  => $this->when($this->image, asset('storage/' . $this->image)),
            'alt_images'     => $this->when($this->alt_images, $this->alt_images ? $this->generateAltImagesUrls() : ''),

            'nights' => $this->when($this->nights, $this->nights),
            'adults' => $this->when($this->adults, $this->adults),
            'children' => $this->when($this->children, $this->children),
            'promoCode' => $this->when($this->promoCode, $this->promoCode),
            'checkInDate' => $this->when($this->checkInDate, date('Y-m-d', strtotime($this->checkInDate))),
            'checkOutDate' => $this->when($this->checkOutDate, date('Y-m-d', strtotime($this->checkOutDate))),

            'member_pay' => $this->when($this->total_price, $this->total_price),
            'public_pay' => $this->when($this->nights, ($this->night_price * $this->nights)),
        ];
    }

    protected function generateAltImagesUrls()
    {
        $altImagesUrls = [];

        foreach ($this->alt_images as $altImage) {
            $altImagesUrls[] = asset('storage/' . $altImage);
        }

        return $altImagesUrls;
    }
}
