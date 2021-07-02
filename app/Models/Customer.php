<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Customer extends User implements HasMedia
{
    use HasApiTokens, Authenticatable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'mobile'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Check if this customer owns the given order.
     *
     * @param Order $order
     * @return bool
     */
    public function hasOrdered(Order $order): bool
    {
        return $this->id == $order->customer_id;
    }

    /**
     * Get all locations
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function locations(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(Location::class);
    }

    /**
     * Get all orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function orders(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all offers
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasManyThrough
     */
    public function offers(): \Illuminate\Database\Eloquent\Relations\hasManyThrough
    {
        return $this->hasManyThrough(Offer::class, Order::class);
    }

    /**
     * Set model image Conversions
     *
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10)
            ->nonOptimized();
    }

    /**
     * Set model image collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('profile_image')
            ->singleFile();
    }
}
