<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Landmark extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'location',
        'city_id',
        'primary_description',
        'secondary_description',
        'internal_image',
        'external_image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
       // 'images' => 'array',
    ];
    

    //one to many relation between cities and landmarks
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    //morph relation between images and landmarks
    public function images() :MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function deleteImages()
    {
        // Delete the associated images
        $this->images()->delete();
    }
}
