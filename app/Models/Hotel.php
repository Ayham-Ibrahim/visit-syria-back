<?php

namespace App\Models;

use App\Models\City;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Hotel extends Model
{
    use HasFactory,SoftDeletes;

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
        'cover_image',
        'logo',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];


    public function images() {
        return $this->morphMany(Image::class,'imageable');
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

    /**
     * Set the city name based on the city_id.
     *
     * @param  mixed  $value
     * @return void
     */
    // public function setCityIdAttribute($value)
    // {
    //     $this->attributes['city_id'] = $value;
    //     // Fetch the city name using the city_id
    //     $cityName = City::find($value)->name;
    //     // Store the city name in the city_name attribute
    //     $this->attributes['city_name'] = $cityName;
    // }

    public function getCityIdAttribute($value) {
        $cityName = City::find($value)->name;
        return $cityName;
    }

}
