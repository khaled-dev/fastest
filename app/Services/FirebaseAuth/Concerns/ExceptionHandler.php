<?php


namespace App\Services\FirebaseAuth\Concerns;


trait ExceptionHandler
{
    /**
     * Generate http response for invalid fbToken field
     *
     * @param $exception
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function invalidFbTokenResponse($exception)
    {
        return $this->invalidArgumentResponse('fbToken', $exception);
    }

    /**
     * Generate http response for invalid argument
     *
     * @param $field
     * @param $exception
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    private function invalidArgumentResponse($field, $exception)
    {
        return response([
            'message' => 'The given data was invalid.',
            'errors' => [
                $field => [
                    $exception->getMessage()
                ],
            ],
        ], 422);
    }
}
