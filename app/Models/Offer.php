<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'price',
        'delivery_time',
    ];

    /**
     * Offer states
     */
    const UNDER_NEGOTIATION = 'under_negotiation';
    const ACCEPTED = 'accepted';
    const COMPLETED = 'completed';
    const CANCELED = 'canceled';

    /**
     * Check if this offer is `under_negotiation`
     *
     * @return bool
     */
    public function isUnderNegotiation(): bool
    {
        return $this->state == static::UNDER_NEGOTIATION;
    }

    /**
     * Get other offer.
     *
     * @param $query
     * @param Offer $offer
     * @return mixed
     */
    public function scopeOtherOffers($query, Offer $offer)
    {
        return $query->where('id', '!=', $offer->id);
    }

    /**
     * change offer state to be `accepted`
     *
     * @return $this
     */
    public function accept(): Offer
    {
        $this->state = static::ACCEPTED;
        $this->save();

        return $this;
    }

    /**
     * Get the order of this offer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
