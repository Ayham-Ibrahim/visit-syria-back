<?php

namespace App\Models;

use Exception;
use App\Models\City;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Restaurant extends Model
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
        'table_price',
        'menu',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    
    ];

     /**
     * Get all of the comments for the document.
     *
     * This function defines a polymorphic relationship between the document model and the comment model,
     * allowing the document to have many comments associated with it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments() : MorphMany
    {
        return $this->morphMany(Comment::class,'commentable');
    }



    public function city() {
        return $this->belongsTo(City::class);
    }

    public function images() {
        return $this->morphMany(Image::class,'imageable');
    }

    public function services() {
        return $this->belongsToMany(Service::class, 'resturant_services');
    }

    // public function getCityIdAttribute($value) {
    //     $cityName = City::find($value)->name;
    //     return $cityName;
    // }


    
    /**
     * Delete the hotel and its associated images.
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($restaurant) {
            $restaurant->images()->each(function ($image) {
                try {
                    // Attempt to delete the file from the filesystem
                    $filePath = public_path($image->path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                } catch (Exception $e) {
                    Log::error("Error deleting file: {$e->getMessage()}");
                }
                // we can make delete for soft delete 
                $image->forceDelete();
            });
        });
    }

}
