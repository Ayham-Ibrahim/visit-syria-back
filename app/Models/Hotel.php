<?php

namespace App\Models;

use Exception;
use App\Models\City;
use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;


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
     * Delete the hotel and its associated images.
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($hotel) {
            $hotel->images()->each(function ($image) {
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
//Relation Morph
    public function Rates(){
     return $this->morphMany(Rate::class,'ratable');
   }
}
