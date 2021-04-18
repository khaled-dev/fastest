<?php


namespace Tests\Json;


trait CustomerJsonResponse
{

    /**
     *
     */
    private static function loginOrStoreJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setData(['customer' => [], 'accessToken'])
            ->setMetadata(['show', 'update', 'updateImages'])
            ->get();
    }

    /**
     *
     */
    private function showJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setData(['customer' => []])
            ->setMetadata(['update', 'updateImages'])
            ->get();
    }

    /**
     *
     */
    private function updateJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setData(['customer' => []])
            ->setMetadata(['show', 'updateImages'])
            ->get();
    }

    /**
     *
     */
    private function updateImagesJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setData(['customer' => []])
            ->setMetadata(['show', 'update'])
            ->get();
    }
}
