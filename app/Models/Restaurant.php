<?php

namespace App\Models;

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
        //
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
}
