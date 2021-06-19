<?php

namespace App\Services\Logic;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use App\Services\Contracts\ICloudMessaging;

class NotificationService
{
    /**
     * Saves the given registration token of a device.
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

    /**
     * Push notification to a given device.
     *
     * @param string $token
     * @param array $data
     * @return array
     */
    public static function pushNotification(string $token, array $data): array
    {
        $firebaseCloudMessaging = App::make(ICloudMessaging::class);

        return $firebaseCloudMessaging
            ->withToken($token)
            ->withNotification($data)
            ->send();
    }
}
