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
            'fb_token' =>  [$exception->getMessage()]
        ]);
    }

    /**
     * Generate validation error response for invalid fb CloudMessaging Token
     *
     * @param $exception
     * @return Response
     */
    protected function invalidFbRegistrationTokenResponse($exception): Response
    {
        return $this->validationErrorResponse([
            'fb_registration_token' =>  [$exception->getMessage()]
        ]);
    }

    /**
     * Generate validation error response
     *
     * @param array $errors
     * @param array $metadata
     * @return Response
     */
    protected function validationErrorResponse(array $errors, array $metadata = []): Response
    {
        return response([
            "message" => "The given data was invalid.",
            "errors" => $errors,
            "meta" => $metadata
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
            "meta" => $metadata,
        ], $status);
    }
}
