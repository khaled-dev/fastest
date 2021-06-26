<?php

namespace App\Listeners;

use App\Events\SendMessage;
use App\Listeners\Concerns\PushNotificationHelper;
use App\Models\Courier;
use App\Models\Message;
use App\Models\Offer;
use App\Models\User;
use App\Services\Logic\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

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
        $sendTo   = $offer->courier;

        if (auth()->user() instanceof Courier) {
            list($sendTo, $sendFrom) = array($sendFrom, $sendTo);
        }

        $this->from($sendFrom)
            ->to($sendTo)
            ->setNotification('notifications.offers.message')
            ->setData('chat', $offer, $message)
            ->push();
    }

}
