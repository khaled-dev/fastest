<?php

namespace App\Listeners;

use App\Events\OfferCompleted;
use App\Listeners\Concerns\PushNotificationHelper;

class SendCompletedOfferNotification
{
    use PushNotificationHelper;

    /**
     * Handle the event.
     *
     * @param  OfferCompleted  $event
     * @return void
     */
    public function handle(OfferCompleted $event)
    {
        $offer = $event->offer;
        $order = $event->offer->order;

        $this->from($order->customer)
            ->to($offer->courier)
            ->setNotification('notifications.offers.completed')
            ->setData('offer-completed', $offer)
            ->push(true);
    }
}
