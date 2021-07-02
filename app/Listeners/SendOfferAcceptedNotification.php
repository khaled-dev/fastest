<?php

namespace App\Listeners;

use App\Events\OfferAccepted;
use App\Listeners\Concerns\PushNotificationHelper;

class SendOfferAcceptedNotification
{
    use PushNotificationHelper;

    /**
     * Handle the event.
     *
     * @param  OfferAccepted  $event
     * @return void
     */
    public function handle(OfferAccepted $event)
    {
        $offer = $event->offer;
        $order = $offer->order;

        $this->from($order->customer)
            ->to($offer->courier)
            ->setNotification('notifications.offers.accepted')
            ->setData('offer-accepted', $offer, null, ['topicName' => $offer->chatTopic()])
            ->push(true);
    }
}
