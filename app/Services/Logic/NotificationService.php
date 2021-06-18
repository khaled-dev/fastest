<?php


namespace App\Services\Logic;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class NotificationService
{
    /**
     * save the given registration token of a device.
     *
     * @param User $user
     * @param string $token
     * @return Model
     */
    public static function saveRegistrationToken(User $user, string $token): Model
    {
        return $user->notificationToken()->create(['token' => $token]);
    }

    /**
     * Saves the notification message to the database.
     *
     * @param User $user
     * @param array $data
     * @return Model
     */
    public static function saveNotification(User $user, array $data): Model
    {
        return $user->notifications()->create([
            'title' => $data['title'],
            'body'  => $data['body'],
            'image_url'  => $data['image_url'],
        ]);
    }
}
