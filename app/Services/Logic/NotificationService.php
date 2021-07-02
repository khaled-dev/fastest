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
        return $user->notificationToken()->updateOrCreate(
            ['resource_type' => get_class($user), 'resource_id' => $user->id],
            ['token' => $token]
        );
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
     * @param array $notification
     * @param array $data
     * @return array
     */
    public static function pushNotification(string $token, array $notification, array $data): array
    {
        $firebaseCloudMessaging = App::make(ICloudMessaging::class);

        if (static::isRegistrationTokenValid($token)) {
            return $firebaseCloudMessaging
                ->withToken($token)
                ->withNotification($notification)
                ->withData($data)
                ->send();
        }

        return [];
    }

    /**
     * Push message/notification to a given topic.
     *
     * @param string $topicName
     * @param array $notification
     * @param array $data
     * @return array
     */
    public static function pushToTopic(string $topicName, array $notification, array $data): array
    {
        $firebaseCloudMessaging = App::make(ICloudMessaging::class);;

        return $firebaseCloudMessaging
            ->withTopic($topicName)
            ->withNotification($notification)
            ->withData($data)
            ->send();
    }

    /**
     * Validate registration token.
     *
     * @param string $token
     * @return bool
     */
    public static function validateRegistrationToken(string $token): bool
    {
        $firebaseCloudMessaging = App::make(ICloudMessaging::class);

        return $firebaseCloudMessaging->validateRegistrationToken($token);
    }

    /**
     * Is registration token valid.
     *
     * @param string $token
     * @return bool
     */
    public static function isRegistrationTokenValid(string $token): bool
    {
        $firebaseCloudMessaging = App::make(ICloudMessaging::class);

        return $firebaseCloudMessaging->isRegistrationTokenValid($token);
    }
}
