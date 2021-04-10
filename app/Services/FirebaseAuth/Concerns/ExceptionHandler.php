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
    protected function AuthorizationExceptionResponse($exception): Response
    {
        return $this->exceptionResponse($exception, 401);
    }

    /**
     * Generate http response for invalid fbToken field
     *
     * @param $exception
     * @param int $status
     * @return Response
     */
    private function exceptionResponse($exception, int $status = 400): Response
    {
        return response([
            'message' => $exception->getMessage(),
            'data' => [],
            'metadata' => [],
        ], $status);
    }

}
