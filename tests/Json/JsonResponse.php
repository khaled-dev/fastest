<?php


namespace Tests\Json;


trait JsonResponse
{
    /**
     * @return array
     */
    private static function unauthenticatedJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setMessage('Unauthenticated.')
            ->get();
    }

    /**
     * @param array $errors
     * @return array
     */
    private static function validationErrorJsonResponse($errors = []): array
    {
        return (new JsonResponseBuilder())
            ->setMessage('The given data was invalid.')
            ->setError($errors)
            ->get();
    }
}
