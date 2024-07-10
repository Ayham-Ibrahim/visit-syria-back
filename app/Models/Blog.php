<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use App\Models\City;
use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'category',
        'main_image',
        'city_id',
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
     * accessor that make the created at vlue in form of 'd-m-Y H:i:s'
     *
     * @return string $date in form of 'd-m-Y'
     */    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value);
        return $date->format('d-m-Y H:i:s');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($blog) {
            $blog->images()->each(function ($image) {
                try {
                    $filePath = public_path($image->path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                } catch (Exception $e) {
                    Log::error("Error deleting file: {$e->getMessage()}");
                }
                $image->forceDelete();
            });
        });
    }
}
