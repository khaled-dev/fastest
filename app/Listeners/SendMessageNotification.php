<?php

namespace App\Listeners;

use App\Models\Courier;
use App\Events\SendMessage;
use App\Listeners\Concerns\PushNotificationHelper;

class SendMessageNotification
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
        $sendTo   = $offer->courier;

        if (auth()->user() instanceof Courier) {
            list($sendTo, $sendFrom) = array($sendFrom, $sendTo);
        }

        $this->from($sendFrom)
            ->to($sendTo)
            ->setNotification('notifications.offers.message')
            ->setData('notification', $offer, $message, ['topicName' => $offer->chatTopic()])
            ->push();
    }
}
