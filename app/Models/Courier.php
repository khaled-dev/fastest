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
     * Get the CourierUpdateRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function courierUpdateRequest(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(CourierUpdateRequest::class);
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
    public function car_type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
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
            ->addMediaCollection('profile_picture')
            ->singleFile();

        $this
            ->addMediaCollection('national_card_picture')
            ->singleFile();

        $this
            ->addMediaCollection('car_license_picture')
            ->singleFile();

        $this
            ->addMediaCollection('driving_license_picture')
            ->singleFile();
    }

}
