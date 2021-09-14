<?php

namespace App\Providers;

use App\Events\OfferRejected;
use App\Events\RequestCancellation;
use App\Events\SendMessage;
use App\Listeners\CreateFirstMessageInChat;
use App\Listeners\SendMessageNotification;
use App\Listeners\PushMessage;
use App\Listeners\SendRejectedOfferNotification;
use App\Listeners\SendRequestCancellationNotification;
use App\Models\Order;
use App\Events\OfferPlaced;
use App\Events\OfferAccepted;
use App\Events\OfferCanceled;
use App\Events\OfferCompleted;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Models\Observers\OrderObserver;
use App\Listeners\RejectOtherOffersOnOrders;
use App\Listeners\SendPlacedOfferNotification;
use App\Listeners\SendOfferAcceptedNotification;
use App\Listeners\SendCanceledOfferNotification;
use App\Listeners\SendCompletedOfferNotification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OfferAccepted::class => [
            RejectOtherOffersOnOrders::class,
            CreateFirstMessageInChat::class,
            SendOfferAcceptedNotification::class,
        ],
        OfferPlaced::class => [
            SendPlacedOfferNotification::class,
        ],
        OfferRejected::class => [
            SendRejectedOfferNotification::class,
        ],
        OfferCanceled::class => [
            SendCanceledOfferNotification::class,
        ],
        RequestCancellation::class => [
            SendRequestCancellationNotification::class,
        ],
        OfferCompleted::class => [
            SendCompletedOfferNotification::class,
        ],
        SendMessage::class => [
            SendMessageNotification::class,
            PushMessage::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Order::observe(OrderObserver::class);
    }
}
