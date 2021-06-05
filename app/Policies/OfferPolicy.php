<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Offer;
use App\Models\Courier;
use App\Models\Order;
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
}
