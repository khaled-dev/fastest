<?php


namespace App\Services\Http;

use \Illuminate\Http\Response;

trait ResponseBuilder
{
    /**
     * Generate validation error response for invalid fbToken
     *
     * @param $exception
     * @return Response
     */
    protected function invalidFbTokenResponse($exception): Response
    {
        return $this->validationErrorResponse([
            'fbToken' =>  [$exception->getMessage()]
        ]);
    }

    /**
     * Generate validation error response
     *
     * @param array $errors
     * @return Response
     */
    protected function validationErrorResponse(array $errors): Response
    {
        return response([
            "message" => "The given data was invalid.",
            "errors" => $errors
        ], 422);
    }

    /**
     * Generate success response
     *
     * @param array $data
     * @param array $metadata
     * @param int $status
     * @return Response
     */
    protected function successResponse(array $data, array $metadata = [], int $status = 200): Response
    {
        return response([
            "message" => "success",
            "data" => $data,
            "metadata" => $metadata,
        ], $status);
    }
}
