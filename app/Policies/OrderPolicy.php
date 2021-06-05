<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the customer can cancel this model.
     *
     * @param Customer $customer
     * @param Order $order
     * @return bool
     */
    public function cancel(Customer $customer, Order $order): bool
    {
        return $customer->hasOrdered($order) && $order->isOpened();
    }
}
