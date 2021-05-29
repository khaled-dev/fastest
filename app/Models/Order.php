<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Order extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'store_id',
        'location_id',
        'description',
        'min_offer_price',
        'max_offer_price',
    ];

    /**
     * Order states
     */
    const OPENED = 'opened';
    const UNDER_NEGOTIATION = 'under_negotiation';
    const IN_PROGRESS = 'in_progress';
    const COMPLETED = 'completed';
    const CANCELED = 'canceled';


    /**
     * Get all opened orders
     *
     * @param $query
     * @return mixed
     */
    public function scopeOpened($query)
    {
        return $query->where('state', static::OPENED);
    }

    /**
     * Get all under_negotiation orders
     *
     * @param $query
     * @return mixed
     */
    public function scopeUnderNegotiation($query)
    {
        return $query->where('state', static::UNDER_NEGOTIATION);
    }

    /**
     * Get all in_progress orders
     *
     * @param $query
     * @return mixed
     */
    public function scopeInProgress($query)
    {
        return $query->where('state', static::IN_PROGRESS);
    }

    /**
     * List all states
     *
     * @return array
     */
    public static function listStates(): array
    {
        return [
            static::OPENED,
            static::UNDER_NEGOTIATION,
            static::IN_PROGRESS,
            static::COMPLETED,
            static::CANCELED,
        ];
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
        $this->addMediaCollection('images');
    }
}
