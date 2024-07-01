<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'category' => $this->category,
            'city' => $this->city->name,
            'city_id' => $this->city_id,
            'created_at' => $this->created_at,
            'main_image' => $this->main_image,
            'images' => $this->images->map(function ($image) {
                return $image->path;
            }),
        ];
    }
}
