<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

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
    const REJECTED = 'rejected';
    const ACCEPTED = 'accepted';
    const COMPLETED = 'completed';
    const CANCELED = 'canceled';

    /**
     * Check if the cancellation time has passed.
     *
     * @return bool
     */
    public function hasCancelTimePassed(): bool
    {
        $cancellationTime = Setting::all()->first()->cancellation_time;

        $minute = Carbon::parse($cancellationTime)->minute;
        $hour   = Carbon::parse($cancellationTime)->hour;
        $hour   = empty($hour) ? 0 : $hour * 60;

        return $this->updated_at->diffInMinutes(now()) > ($hour + $minute);
    }

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
     * Check if this offer is ended yet
     *
     * @return bool
     */
    public function isNotEnded(): bool
    {
        return !in_array($this->state, [static::COMPLETED, static::REJECTED, static::CANCELED]);
    }

    /**
     * Check if this offer is `accepted`
     *
     * @return bool
     */
    public function isAccepted(): bool
    {
        return $this->state == static::ACCEPTED;
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
    public function markAsAccepted(): Offer
    {
        $this->state = static::ACCEPTED;
        $this->save();

        return $this;
    }

    /**
     * change offer state to be `completed`
     *
     * @return $this
     */
    public function markAsCompleted(): Offer
    {
        $this->state = static::COMPLETED;
        $this->save();

        return $this;
    }

    /**
     * change offer state to be `canceled`
     *
     * @return $this
     */
    public function markAsCanceled(): Offer
    {
        $this->state = static::CANCELED;
        $this->save();

        return $this;
    }

    /**
     * Request cancellation approve.
     *
     * @param User $user
     * @return $this
     */
    public function requestCancellation(User $user): Offer
    {
        $this->is_cancel_requested = $user->id;
        $this->save();

        return $this;
    }

    /**
     * check if given user has Request cancellation.
     *
     * @param User $user
     * @return bool
     */
    public function hasUserRequestedCancellation(User $user): bool
    {
        return $this->is_cancel_requested == $user->id;
    }

    /**
     * check if this offer has cancellation Request.
     *
     * @return bool
     */
    public function hasCancellationRequest(): bool
    {
        return ! empty($this->is_cancel_requested);
    }

    /**
     * change offer state to be `rejected`
     *
     * @return $this
     */
    public function markAsRejected(): Offer
    {
        $this->state = static::REJECTED;
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

    /**
     * Get the courier who placed the offer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    /**
     * Get all messages of this offer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Message::class);
    }

}
