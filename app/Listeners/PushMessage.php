<?php

namespace App\Listeners;

use App\Models\Courier;
use App\Events\SendMessage;
use App\Listeners\Concerns\PushNotificationHelper;

class PushMessage
{
    use PushNotificationHelper;

    /**
     * Handle the event.
     *
     * @param  SendMessage  $event
     * @return void
     */
    public function handle(SendMessage $event)
    {
        $message  = $event->message;
        $offer    = $event->message->offer;
        $sendFrom = $offer->order->customer;

        if (auth()->user() instanceof Courier) {
            $sendFrom = $offer->courier;
        }

        $this->from($sendFrom)
            ->toTopic("{$offer->id}-chat-topic")
            ->setNotification('notifications.offers.message')
            ->setData('chat', $offer, $message)
            ->push();
    }

}
