<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
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
            'city_id' => $this->city_id,
            'primary_description' => $this->primary_description,
            'secondary_description' => $this->secondary_description,
            'table_price' => $this->table_price,
            'cover_image' => $this->cover_image,
            'logo' => $this->logo,
            'menu' => $this->menu,
            'images' => $this->images->pluck('path'),
        ];
    }
}
