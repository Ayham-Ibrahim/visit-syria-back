<?php

namespace App\Models;

use App\Models\Blog;
use App\Models\Hotel;
use App\Models\Landmark;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class City extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];

    public function resturants() {
        return $this->hasMany(Restaurant::class);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }


    //one to many relation between cities and landmarks
    public function landmarks()
    {
        return $this->hasMany(Landmark::class);
    }
}
