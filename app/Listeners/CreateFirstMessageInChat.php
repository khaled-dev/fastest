<?php

namespace App\Listeners;

use App\Events\OfferAccepted;
use App\Listeners\Concerns\PushNotificationHelper;
use App\Models\Order;

class CreateFirstMessageInChat
{
    use PushNotificationHelper;

    /**
     * Handle the event.
     *
     * @param  OfferAccepted  $event
     * @return void
     */
    public function handle(OfferAccepted $event)
    {
        // find order
        $offer = $event->offer;
        $order = $offer->order;

        // add order description in a message
        $order->customer->messages()->create([
            'body' => $order->description,
            'offer_id' => $offer->id,
        ]);
        
        // add order images in messages
        $mediaItems = $order->getMedia('images');
        $mediaItems = $mediaItems->map(function ($media) {
            return $media->getPath();
        })->toArray();

        if ($mediaItems) {
            $message = $order->customer->messages()->create(['offer_id' => $offer->id]);
            $message->addMultipleMediaToCollection($mediaItems, 'images');
        }

    }
}
