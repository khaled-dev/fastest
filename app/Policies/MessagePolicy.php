<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Offer;
use App\Models\Courier;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the User can send a message.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Offer  $offer
     * @return mixed
     */
    public function send(User $user, Offer $offer)
    {
        return $offer->isAccepted() && (
            ($user instanceof Courier && $user->hasProposed($offer))
            || ($user instanceof Customer && $user->hasOrdered($offer->order))
        );
    }
}
