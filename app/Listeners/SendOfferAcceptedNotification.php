<?php

namespace App\Listeners;

use App\Events\OfferAccepted;
use Illuminate\Support\Facades\Log;
use App\Services\Logic\NotificationService;

class SendOfferAcceptedNotification
{
    /**
     * Handle the event.
     *
     * @param  OfferAccepted  $event
     * @return void
     */
    public function handle(OfferAccepted $event)
    {
        $offer    = $event->offer;
        $order    = $offer->order;
        $customer = $order->customer;
        $courier  = $offer->courier;

        $notification = [
            'title'     => __('notifications.offers.accepted.title'),
            'body'      => __('notifications.offers.accepted.body'),
            'image_url' => $customer->profile_picture,
        ];

        NotificationService::saveNotification($courier, $notification);

        if ($token = $courier->notificationToken) {
            NotificationService::pushNotification($token, $notification);
            return;
        }

        Log::channel('handwrite')->warning("courier with ID {$courier->id} dose not have fcm-token");
    }
}
