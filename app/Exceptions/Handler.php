<?php

namespace App\Exceptions;

use Throwable;
use App\Services\Http\ResponseBuilder;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Auth\Access\AuthorizationException;
use Kreait\Firebase\Exception\InvalidArgumentException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Services\FirebaseAuth\Concerns\ExceptionHandler as FbExceptionHandler;

class Handler extends ExceptionHandler
{
    use FbExceptionHandler, ResponseBuilder;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // firebase token handler
        if ($exception instanceof InvalidArgumentException || $exception instanceof  InvalidToken) {
            return $this->invalidFbTokenResponse($exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->AuthorizationExceptionResponse($exception);
        }

        return parent::render($request, $exception);

    }
}
