<?php


namespace App\Services\FirebaseCloudMessaging;


use Kreait\Firebase\Messaging;
use App\Services\Contracts\ICloudMessaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Services\Exceptions\InvalidFirebaseRegistrationTokenException;

class FirebaseCloudMessaging implements ICloudMessaging
{

    /**
     * Messaging object.
     *
     * @var Messaging
     */
    protected Messaging $messaging;

    /**
     * Messaging object.
     *
     * @var array
     */
    protected array $notifications;

    /**
     * Messaging object.
     *
     * @var array
     */
    protected array $data;

    /**
     * Messaging object.
     *
     * @var array
     */
    protected array $target;

    /**
     * FirebaseCloudMessaging constructor.
     *
     * @param Messaging $messaging
     */
    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * Adds notification data message [title:'', body:'', imageUrl:'']
     *
     * @param array $notifications
     * @return $this
     */
    public function withNotification(array $notifications): FirebaseCloudMessaging
    {
        $this->notifications = $notifications;

        return $this;
    }

    /**
     * Adds Extra data.
     *
     * @param array $data
     * @return $this
     */
    public function withData(array $data): FirebaseCloudMessaging
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Adds a topic to publish to.
     *
     * @param string $topic
     * @return $this
     */
    public function withTopic(string $topic): FirebaseCloudMessaging
    {
        $this->target['type'] = 'topic';
        $this->target['value'] = $topic;

        return $this;
    }

    /**
     * Adds condition to publish on.
     *
     * @param string $condition
     * @return $this
     */
    public function withCondition(string $condition): FirebaseCloudMessaging
    {
        $this->target['type'] = 'condition';
        $this->target['value'] = $condition;

        return $this;
    }

    /**
     * Adds device token to publish to.
     *
     * @param string $token
     * @return $this
     */
    public function withToken(string $token): FirebaseCloudMessaging
    {
        $this->target['type'] = 'token';
        $this->target['value'] = $token;

        return $this;
    }

    /**
     * Sends a cloud message.
     *
     * @return array
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Kreait\Firebase\Exception\MessagingException
     */
    public function send(): array
    {
        $message = CloudMessage::withTarget($this->target['type'], $this->target['value'])
            ->withNotification(
                Notification::create(
                    $this->notifications['title'], $this->notifications['body'], $this->notifications['imageUrl']
                )
            )
            ->withData($this->data);


        return $this->messaging->send($message);
    }


    /**
     * It validate firebase cloud messaging registration token.
     *
     * @throws \Kreait\Firebase\Exception\MessagingException
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws InvalidFirebaseRegistrationTokenException
     */
    public function validateRegistrationToken(string $token): bool
    {
        $validation  = $this->messaging->validateRegistrationTokens($token);

        if (empty($validation['valid'])) {
            throw new InvalidFirebaseRegistrationTokenException();
        }

        return true;
    }
}
