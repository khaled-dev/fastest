<?php

namespace App\Providers;

use App\Models\Order;
use App\Events\OfferPlaced;
use App\Events\OfferAccepted;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Models\Observers\OrderObserver;
use App\Listeners\CancelOtherOffersOnOrders;
use App\Listeners\SendPlacedOfferNotification;
use App\Listeners\SendOfferAcceptedNotification;
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
            CancelOtherOffersOnOrders::class,
            SendOfferAcceptedNotification::class,
        ],
        OfferPlaced::class => [
            SendPlacedOfferNotification::class,
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
