<?php

namespace App\Listeners;

use App\Events\OfferRejected;
use App\Listeners\Concerns\PushNotificationHelper;
use App\Models\Courier;

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
        $sendFrom = $offer->order->customer;
        $sendTo   = $offer->courier;

        if (auth()->user() instanceof Courier) {
            list($sendTo, $sendFrom) = array($sendFrom, $sendTo);
        }

        $this->from($sendFrom)
            ->to($sendTo)
            ->setNotification('notifications.offers.rejected')
            ->setData('offer-rejected', $offer)
            ->push(true);
    }
}
