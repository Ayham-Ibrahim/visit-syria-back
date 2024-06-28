<?php

namespace App\Models;

use Exception;
use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class About extends Model
{
    use HasFactory,SoftDeletes;

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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];
    public function images()
    {
      return $this->MorphMany(Image::class,'imageable');
    }


    /**
     * Delete the hotel and its associated images.
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($about) {
            $about->images()->each(function ($image) {
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
