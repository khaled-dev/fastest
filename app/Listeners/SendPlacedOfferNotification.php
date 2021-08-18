<?php

namespace App\Listeners;

use App\Events\OfferPlaced;
use App\Listeners\Concerns\PushNotificationHelper;

class SendPlacedOfferNotification
{
    use PushNotificationHelper;

    /**
     * Handle the event.
     *
     * @param OfferPlaced $event
     * @return void
     */
    public function handle(OfferPlaced $event)
    {
        $offer = $event->offer;
        $order = $event->offer->order;

        $this->from($offer->courier)
            ->to($order->customer)
            ->setNotification('notifications.offers.placed')
            ->setData('offer-placed', $order)
            ->push(true);
    }
}
