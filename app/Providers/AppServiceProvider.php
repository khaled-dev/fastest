<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FirebaseAuth\FirebaseAuth;
use App\Services\Contracts\ICloudMessaging;
use App\Services\Contracts\IAuthenticateOTP;
use App\Services\FirebaseCloudMessaging\FirebaseCloudMessaging;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        IAuthenticateOTP::class => FirebaseAuth::class,
        ICloudMessaging::class => FirebaseCloudMessaging::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
