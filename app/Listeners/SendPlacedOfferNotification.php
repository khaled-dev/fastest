<?php

namespace App\Listeners;

use App\Events\OfferPlaced;
use Illuminate\Support\Facades\Log;
use App\Services\Logic\NotificationService;

class SendPlacedOfferNotification
{
    /**
     * Handle the event.
     *
     * @param OfferPlaced $event
     * @return void
     */
    public function handle(OfferPlaced $event)
    {
        $offer    = $event->offer;
        $order    = $event->offer->order;
        $customer = $order->customer; // send to

        $notification = [
            'title'     => __('notifications.offers.placed.title'),
            'body'      => __('notifications.offers.placed.body'),
            'image_url' => $offer->courier->profile_picture,
        ];

        NotificationService::saveNotification($customer, $notification);

        if (!empty($customer->notificationToken) && $token = $customer->notificationToken->token) {
            NotificationService::pushNotification($token, $notification);
            return;
        }

        Log::channel('handwrite')->warning("customer with ID {$customer->id} dose not have fcm-token");
    }
}
