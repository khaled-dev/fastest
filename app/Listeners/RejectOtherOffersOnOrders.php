<?php

namespace App\Listeners;

use App\Models\Offer;
use App\Events\OfferAccepted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelOtherOffersOnOrders implements shouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  OfferAccepted  $event
     * @return void
     */
    public function handle(OfferAccepted $event)
    {
        $otherOffers = $event->offer->order->offers()->otherOffers($event->offer);

        $otherOffers->update([
            'state' => Offer::REJECTED,
        ]);
    }
}
