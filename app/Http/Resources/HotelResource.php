<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'city' => $this->city->name,
            'primary_description' => $this->primary_description,
            'secondary_description' => $this->secondary_description,
            'price' => $this->price,
            'cover_image' => $this->cover_image,
            'logo' => $this->logo,
            'images' => $this->images->map(function ($image) {
                return $image->path;
            }),
        ];
    }
}
