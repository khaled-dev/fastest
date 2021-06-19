<?php


namespace App\Services\Exceptions;

use Exception;
use Throwable;
use Kreait\Firebase\Exception\FirebaseException;

class InvalidFirebaseRegistrationTokenException extends Exception implements FirebaseException
{
    /**
     * @var
     */
    protected $message;

    /**
     * InvalidFirebaseRegistrationTokenException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = empty($message) ? 'Invalid firebase registration token' : $message;
    }
}
