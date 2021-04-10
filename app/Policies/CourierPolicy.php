<?php

namespace App\Policies;

use App\Models\Courier;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourierPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Courier $courier
     * @return bool
     */
    public function update(User $user, Courier $courier)
    {
        return $user->isAdmin()
        || (
            $user->is($courier)
            && ! $courier->is_active
        );
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Courier $resource
     * @return bool
     */
    public function view(User $user, Courier $courier)
    {
        return true;
    }
}
