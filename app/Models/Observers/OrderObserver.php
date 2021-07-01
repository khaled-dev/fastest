<?php

namespace App\Models\Observers;

use App\Models\Order;
use App\Models\Setting;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     *
     * @param  Order  $order
     * @return void
     */
    public function creating(Order $order)
    {
        $settings = Setting::all()->first();

        $order->min_offer_price = $settings->min_offer_price;
        $order->max_offer_price = $settings->max_offer_price;
    }
}
