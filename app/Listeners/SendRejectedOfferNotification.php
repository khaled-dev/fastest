<?php

namespace App\Listeners;

use App\Events\OfferRejected;
use App\Listeners\Concerns\PushNotificationHelper;

class SendRejectedOfferNotification
{
    use PushNotificationHelper;

    /**
     * Handle the event.
     *
     * @param  OfferRejected $event
     * @return void
     */
    public function handle(OfferRejected $event)
    {
        $offer = $event->offer;
        $order = $event->offer->order;

        $this->from($offer->customer)
            ->to($order->courier)
            ->setNotification('notifications.offers.rejected')
            ->setData('offer-rejected', $offer)
            ->push(true);
    }
}
