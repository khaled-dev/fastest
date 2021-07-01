<?php

namespace App\Listeners;

use App\Events\OfferCanceled;
use App\Events\RequestCancellation;
use App\Listeners\Concerns\PushNotificationHelper;
use App\Models\Courier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRequestCancellationNotification
{
    use  PushNotificationHelper;

    /**
     * Handle the event.
     *
     * @param  RequestCancellation $event
     * @return void
     */
    public function handle(RequestCancellation $event)
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
            ->setNotification('notifications.offers.request_cancellation')
            ->setData('offer-request-cancellation', $offer)
            ->push(true);
    }
}
