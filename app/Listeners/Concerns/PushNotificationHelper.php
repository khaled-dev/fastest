<?php


namespace App\Listeners\Concerns;


use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Services\Logic\NotificationService;

trait PushNotificationHelper
{

    /**
     * Holds notification data.
     *
     * @var array
     */
    private array $notification;

    /**
     * Holds extra data.
     *
     * @var array
     */
    private array $data;

    /**
     * Holds the sender user model.
     *
     * @var Model
     */
    private Model $from;

    /**
     * Holds the receiver user model.
     *
     * @var Model
     */
    private Model $to;

    /**
     * Holds the topic name.
     *
     * @var string
     */
    private string $topic;

    /**
     * Push the prepared notification.
     *
     * @param bool|null $saveNotificationRecord
     * @return void
     */
    protected function push(?bool $saveNotificationRecord = false)
    {
        $sendTo = $this->to;

        if ($saveNotificationRecord) {
            NotificationService::saveNotification($sendTo, $this->notification);
        }

        if (!empty($sendTo->notificationToken) && $token = $sendTo->notificationToken->token) {
            NotificationService::pushNotification($token, $this->notification, $this->data);
            return;
        }

        if (!empty($this->topic)) {
            NotificationService::pushToTopic($this->topic, $this->notification, $this->data);
            return;
        }

        Log::channel('handwrite')->warning(
            "user with ID {$sendTo->id}/type " . get_class($sendTo) . " dose not have fcm-token"
        );
    }

    /**
     * Set the sender user model.
     *
     * @param Model $fromUser
     * @return $this
     */
    protected function from(Model $fromUser): PushNotificationHelper
    {
        $this->from = $fromUser;

        return $this;
    }

    /**
     * Set user model to send to.
     *
     * @param Model $toUser
     * @return $this
     */
    protected function to(Model $toUser): PushNotificationHelper
    {
        $this->to = $toUser;

        return $this;
    }

    /**
     * Set topic name to push to.
     *
     * @param string $topic
     * @return $this
     */
    protected function toTopic(string $topic): PushNotificationHelper
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Set notification to push.
     *
     * @param string $localParentKey
     * @return $this
     */
    protected function setNotification(string $localParentKey): PushNotificationHelper
    {
        $this->notification = [
            'title'     => __("{$localParentKey}.title"),
            'body'      => __("{$localParentKey}.body"),
            'image_url' => $this->from->profile_picture,
        ];

        return $this;
    }

    /**
     * Set Extra data to push.
     *
     * @param string $type
     * @param Model $resource
     * @param Message|null $message
     * @param array $extraData
     * @return $this
     */
    protected function setData(string $type, Model $resource, ?Message $message = null, array $extraData = []): PushNotificationHelper
    {
        if (! empty($message)) {
            $extraData = array_merge([
                'message'    => $message->body,
                'senderId'   => $message->sender_id,
                'senderType' => $this->getType($message->sender_type),
                'topicName'  => $this->topic ?? null,
            ], $extraData);
        }

        $this->data = array_merge([
            'type'         => $type,
            'resourceId'   => $resource->id,
            'resourceType' => $this->getType(get_class($resource)),
        ], $extraData);

        return $this;
    }

    /**
     * @param string $modelPath
     * @return string
     */
    private function getType(string $modelPath): string
    {
        $senderModel = explode('\\', $modelPath);
        return lcfirst(end($senderModel));
    }
}
