<?php

namespace App\Listeners;

use App\Events\OfferPlaced;
use App\Models\Customer;
use App\Models\Order;
use App\Services\Contracts\ICloudMessaging;
use App\Services\FirebaseCloudMessaging\FirebaseCloudMessaging;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param  OfferPlaced  $event
     * @return void
     */
    public function handle(OfferPlaced $event)
    {
        /** @var Order $order */
        /** @var Customer $customer */
        $order    = $event->offer->order();
        $customer = $order->order()->customer();

        // TODO: add notification data
        $notification = [
            'title' => '',
            'body' => '',
            'image_url' => '',
        ];

        // save notification
        $customer->notifications()->create($notification);

        // TODO: check the result and log

        // get notification token
        if ($token = $customer->notificationToken()) {
            // send notification
            $this->cloudMessaging
                ->withToken($token)
                ->withNotification($notification)
                ->send();
        }

    }
}
