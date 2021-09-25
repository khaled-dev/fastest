<?php

namespace App\Listeners;

use App\Models\Courier;
use App\Events\OfferRejectCancellationRequest;
use App\Listeners\Concerns\PushNotificationHelper;

class SendCancellationRequestRejectedNotification
{
    use PushNotificationHelper;

    /**
     * Handle the event.
     *
     * @param  OfferRejectCancellationRequest  $event
     * @return void
     */
    public function handle(OfferRejectCancellationRequest $event)
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
            ->setNotification('notifications.offers.reject_cancellation_request')
            ->setData('offer-cancellation-request-rejected', $offer)
            ->push(true);
    }
}
