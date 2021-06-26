<?php

namespace App\Listeners;

use App\Models\Courier;
use App\Events\OfferCanceled;
use App\Listeners\Concerns\PushNotificationHelper;

class SendCanceledOfferNotification
{
    use  PushNotificationHelper;

    /**
     * Handle the event.
     *
     * @param  OfferCanceled  $event
     * @return void
     */
    public function handle(OfferCanceled $event)
    {
        $offer    = $event->offer;
        $order    = $event->offer->order;

        $sendFrom = $order->customer;
        $sendTo   = $offer->courier;

        if (auth()->user() instanceof Courier) {
            list($sendTo, $sendFrom) = array($sendFrom, $sendTo);
        }

        $this->from($sendFrom)
            ->to($sendTo)
            ->setNotification('notifications.offers.canceled')
            ->setData('offer-canceled', $offer)
            ->push(true);
    }
}
