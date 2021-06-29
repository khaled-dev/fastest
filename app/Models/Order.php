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
     * change order state to be `in_progress`
     *
     * @return $this
     */
    public function markAsInProgress(): Order
    {
        $this->state = static::IN_PROGRESS;
        $this->save();

        return $this;
    }

    /**
     * change order state to be `completed`
     *
     * @return $this
     */
    public function markAsCompleted(): Order
    {
        $this->state = static::COMPLETED;
        $this->save();

        return $this;
    }

    /**
     * change order state to be `canceled`
     *
     * @return $this
     */
    public function markAsCanceled(): Order
    {
        $this->state = static::CANCELED;
        $this->save();

        return $this;
    }

    /**
     * Get max offer price for this order.
     *
     * @return mixed
     */
    public function getMaxOfferPriceAttribute()
    {
        return $this->offers()->max('price') ?? Setting::all()->first()->max_offer_price;
    }

    /**
     * change order state to be `canceled`
     *
     * @return bool
     */
    public function isOpened(): bool
    {
       return in_array($this->state, [static::OPENED, static::UNDER_NEGOTIATION]);
    }

    /**
     * Get all order offers
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function offers(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Get the customer who requested the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the store of this order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get all opened orders
     *
     * @param $query
     * @return mixed
     */
    public function scopeOpened($query)
    {
        return $query->forGivenState(static::OPENED);
    }

    /**
     * Get all under_negotiation orders
     *
     * @param $query
     * @return mixed
     */
    public function scopeUnderNegotiation($query)
    {
        return $query->forGivenState(static::UNDER_NEGOTIATION);
    }

    /**
     * Get all in_progress orders
     *
     * @param $query
     * @return mixed
     */
    public function scopeInProgress($query)
    {
        return $query->forGivenState(static::IN_PROGRESS);
    }

    /**
     * Get orders filtered by given state
     *
     * @param $query
     * @param $state
     * @return mixed
     */
    public function scopeForGivenState($query, $state)
    {
        return $query->where('state', $state);
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
