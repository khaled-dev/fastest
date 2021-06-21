<?php

namespace App\Listeners;

use App\Events\OfferCompleted;
use App\Services\Logic\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendCompletedOfferNotification
{
    /**
     * Handle the event.
     *
     * @param  OfferCompleted  $event
     * @return void
     */
    public function handle(OfferCompleted $event)
    {
        $offer    = $event->offer;
        $order    = $event->offer->order;
        $customer = $order->customer;
        $courier  = $offer->courier; // send to

        $notification = [
            'title'     => __('notifications.offers.completed.title'),
            'body'      => __('notifications.offers.completed.body'),
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
