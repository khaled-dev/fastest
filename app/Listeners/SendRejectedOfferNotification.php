<?php

namespace App\Listeners;

use App\Events\OfferCanceled;
use App\Events\OfferRejected;
use App\Services\Logic\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendRejectedOfferNotification
{
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
        $customer = $order->customer;
        $courier  = $offer->courier; // send to

        $notification = [
            'title'     => __('notifications.offers.rejected.title'),
            'body'      => __('notifications.offers.rejected.body'),
            'image_url' => $customer->profile_picture,
        ];

        NotificationService::saveNotification($courier, $notification);

        if ($token = $courier->notificationToken) {
            NotificationService::pushNotification($token, $notification);
            return;
        }

        Log::channel('handwrite')->warning("ccourier with ID {$courier->id} dose not have fcm-token");
    }
}
