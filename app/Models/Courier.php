<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Courier extends User implements HasMedia
{
    use HasApiTokens, Authenticatable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'territory_id',
        'city_id',
        'car_type_id',
        'nationality_id',
        'bank_id',
        'name',
        'national_number',
        'gender',
        'car_number',
        'iban',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the courier's profile picture.
     *
     * @return string
     */
    public function getProfilePictureAttribute(): string
    {
        return $this->getFirstMediaUrl('profile_image');
    }

    /**
     * Check if this courier owns the given offer.
     *
     * @param Offer $offer
     * @return bool
     */
    public function hasProposed(Offer $offer): bool
    {
        return $this->id == $offer->courier_id;
    }


    /**
     * Filter by mobile number
     *
     * @param $query
     * @param string $mobile
     * @return mixed
     */
    public function scopeForMobile($query, string $mobile)
    {
        return $query->where('mobile', $mobile);
    }

    /**
     * Filter with `is_active` field is false
     *
     * @param $query
     * @return mixed
     */
    public function scopeNotActive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Check if this courier has no working offers.
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return !$this->offers()->whereIn('state', [Offer::ACCEPTED, Offer::UNDER_NEGOTIATION])->exists();
    }

    /**
     * Get the CourierUpdateRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function courierUpdateRequest(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(CourierUpdateRequest::class);
    }

    /**
     * Get all courier offers
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function offers(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(Offer::class);
    }


    /**
     * Get all orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function orders(): \Illuminate\Database\Eloquent\Relations\belongsToMany
    {
        return $this->belongsToMany(Order::class, 'offers', 'courier_id', 'order_id')->distinct();
//        return Order::whereIn('id', $this->offers()->select('order_id')->distinct() ?? []);
    }

    /**
     * Get the territory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function territory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    /**
     * Get the city
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Get the nationality
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nationality(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }

    /**
     * Get the city_type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CarType::class);
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

        $this
            ->addMediaCollection('national_card_image')
            ->singleFile();

        $this
            ->addMediaCollection('car_license_image')
            ->singleFile();

        $this
            ->addMediaCollection('driving_license_image')
            ->singleFile();
    }
}
