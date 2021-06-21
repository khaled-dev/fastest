<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Offer;
use App\Models\Courier;
use App\Models\Order;
use App\Nova\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the Courier can place an offer.
     *
     * @param  \App\Models\Courier $courier
     * @param  \App\Models\Order  $order
     * @return mixed
     */
    public function store(Courier $courier, Order $order)
    {
        return $courier->isAvailable() && $order->isOpened();
    }

    /**
     * Determine whether the Customer can accept given offer.
     *
     * @param  \App\Models\Customer $customer
     * @param  \App\Models\Offer  $offer
     * @return mixed
     */
    public function accept(Customer $customer, Offer $offer)
    {
        return $customer->hasOrdered($offer->order)
            && $offer->order->isOpened()
            && $offer->isUnderNegotiation();
    }

    /**
     * Determine whether the Customer can cancel given offer.
     *
     * @param  User $user
     * @param  Offer  $offer
     * @return mixed
     */
    public function cancel(User $user, Offer $offer)
    {
        // TODO: reject after some time

        return
            ($user instanceof Courier && $user->hasProposed($offer) && $offer->isUnderNegotiation())
            || ($user instanceof Customer && $user->hasOrdered($offer->order) && $offer->isAccepted());
    }

    /**
     * Determine whether the Customer can reject given offer.
     *
     * @param Customer $customer
     * @param Offer $offer
     * @return mixed
     */
    public function reject(Customer $customer, Offer $offer)
    {
        return $customer->hasOrdered($offer->order) && $offer->isUnderNegotiation();
    }

    /**
     * Determine whether the Customer can complete given offer.
     *
     * @param  \App\Models\Customer $customer
     * @param  \App\Models\Offer  $offer
     * @return mixed
     */
    public function complete(Customer $customer, Offer $offer)
    {
        return $customer->hasOrdered($offer->order)
            && $offer->isAccepted();
    }
}
