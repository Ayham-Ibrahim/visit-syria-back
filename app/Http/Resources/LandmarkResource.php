<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LandmarkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'location'              => $this->location,
            'primary_description'   => $this->primary_description,
            'secondary_description' => $this->secondary_description,
            'internal_image'        => $this->internal_image,
            'external_image'        => $this->external_image,
            'city'                  => $this->city->name,
            'city_id'                  => $this->city_id,
            'images'                => $this->images->pluck('path'),
        ];
    }
}
