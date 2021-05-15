<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'place_id',
        'lat',
        'lng',
        'formatted_address',
        'business_status',
        'name',
        'icon',
        'rating',
        'user_ratings_total',
    ];

    /**
     * Search in stores by store name.
     *
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeForNameLike($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }
}
