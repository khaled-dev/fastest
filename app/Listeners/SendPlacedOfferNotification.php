<?php

namespace App\Listeners;

use App\Events\OfferPlaced;
use App\Models\Customer;
use App\Models\Offer;
use App\Models\Order;
use App\Services\Contracts\ICloudMessaging;
use App\Services\FirebaseCloudMessaging\FirebaseCloudMessaging;
use App\Services\Logic\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendPlacedOfferNotification
{

    /**
     * cloudMessaging Object.
     *
     * @var ICloudMessaging
     */
    protected ICloudMessaging $cloudMessaging;

    public function __construct(ICloudMessaging $cloudMessaging)
    {
        $this->cloudMessaging = $cloudMessaging;
    }

    /**
     * Handle the event.
     *
     * @param OfferPlaced $event
     * @return void
     */
    public function handle(OfferPlaced $event)
    {
        /** @var Offer $offer */
        /** @var Order $order */
        /** @var Customer $customer */
        $offer    = $event->offer;
        $order    = $event->offer->order;
        $customer = $order->customer;

        $notification = [
            'title' => __('notifications.offers.placed.title'),
            'body' => __('notifications.offers.placed.body'),
            'image_url' => $offer->courier->profile_picture,
        ];

        NotificationService::saveNotification($customer, $notification);

        if ($token = $customer->notificationToken) {
            // send notification
            $this->cloudMessaging
                ->withToken($token)
                ->withNotification($notification)
                ->send();

            return;
        }

        Log::warning("customer with ID {$customer->id} dose not have fcm-token");
    }
}
