<?php


namespace App\Services\FirebaseAuth\Concerns;


use Illuminate\Http\Response;

trait ExceptionHandler
{

    /**
     * Generate http response for unauthorized action
     *
     * @param $exception
     * @return Response
     */
    protected function authorizationExceptionResponse($exception): Response
    {
        return $this->exceptionResponse($exception->getMessage(), 403);
    }

    /**
     * Generate http response for authenticated action
     *
     * @param $exception
     * @return Response
     */
    protected function authenticationExceptionResponse($exception): Response
    {
        return $this->exceptionResponse($exception->getMessage(), 401);
    }

    /**
     * Generate http response for not found action
     *
     * @return Response
     */
    protected function notFoundExceptionResponse(): Response
    {
        return $this->exceptionResponse("Not Found", 404);
    }

    /**
     * Generate http response for invalid fbToken field
     *
     * @param string $message
     * @param int $status
     * @return Response
     */
    private function exceptionResponse(string $message, int $status = 400): Response
    {
        return response([
            'message' => $message,
            'data' => [],
            'meta' => [],
        ], $status);
    }

}
