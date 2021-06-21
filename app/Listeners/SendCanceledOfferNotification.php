<?php

namespace App\Listeners;

use App\Events\OfferCanceled;
use App\Models\Courier;
use App\Services\Logic\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendCanceledOfferNotification
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
        $courier  = $offer->courier;

        $sendFrom = $customer;
        $sendTo = $courier;

        if (auth()->user() instanceof Courier) {
            $sendFrom = $courier;
            $sendTo = $customer;
        }

        $notification = [
            'title'     => __('notifications.offers.canceled.title'),
            'body'      => __('notifications.offers.canceled.body'),
            'image_url' => $sendFrom->profile_picture,
        ];

        NotificationService::saveNotification($sendTo, $notification);

        if ($token = $sendTo->notificationToken) {
            NotificationService::pushNotification($token, $notification);
            return;
        }

        Log::channel('handwrite')->warning(
            "user with ID {$sendTo->id}/type " . get_class($sendTo) . " dose not have fcm-token"
        );
    }
}
