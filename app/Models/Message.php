<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use App\Models\Concerns\MediaWork;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Message extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, MediaWork;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'offer_id',
        'body',
    ];

    /**
     * Get the sender model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function sender(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the offer model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function offer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Offer::class);
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
