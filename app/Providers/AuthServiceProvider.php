<?php

namespace App\Providers;

use App\Models\Courier;
use App\Models\Offer;
use App\Models\Order;
use App\Policies\CourierPolicy;
use App\Policies\OfferPolicy;
use App\Policies\OrderPolicy;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Courier::class => CourierPolicy::class,
         Offer::class => OfferPolicy::class,
         Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (! $this->app->routesAreCached()) {
            Passport::routes();
        }
    }
}
