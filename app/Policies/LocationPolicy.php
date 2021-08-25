<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Location;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given post can be deleted by the customer.
     *
     * @param  Customer $customer
     * @param  Location $location
     * @return bool
     */
    public function delete(Customer $customer, Location $location)
    {
        return $location->customer()->is($customer);
    }
}
