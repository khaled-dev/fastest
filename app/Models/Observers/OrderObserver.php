<?php

namespace App\Models\Observers;

use App\Models\Order;

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
        // TODO: make it dynamic
        $order->min_offer_price = 10.001;
        $order->max_offer_price = 100.01;
    }
}
