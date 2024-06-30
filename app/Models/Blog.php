<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

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
        // 'category',
        'main_image',
        // 'city_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];

    // public static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function ($blog) {
    //         $blog->images()->each(function ($image) {
    //             try {
    //                 $filePath = public_path($image->path);
    //                 if (file_exists($filePath)) {
    //                     unlink($filePath);
    //                 }
    //             } catch (Exception $e) {
    //                 Log::error("Error deleting file: {$e->getMessage()}");
    //             }
    //             $image->forceDelete();
    //         });
    //     });
    // }
}
