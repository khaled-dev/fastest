<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Notification  $notification
     * @return bool
     */
    public function view(User $user, Notification $notification): bool
    {
        return $notification->notifiable()->is($user);
    }
}
