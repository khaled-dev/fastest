<?php


namespace App\Models\Concerns;


use App\Models\Notification;
use App\Models\NotificationToken;

trait Notifiable
{

    /**
     * Get the notify token for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function notificationToken()
    {
        return $this->morphOne(NotificationToken::class, 'resource');
    }

    /**
     * get all notification for this notifiable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
