<?php


namespace Tests\Json;


trait CourierJsonResponse
{
    /**
     *
     */
    private function storeJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setData(['courier' => [], 'accessToken'])
            ->setMetadata(['update'])
            ->get();
    }

    /**
     *
     */
    private function loginJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setData(['courier' => [], 'accessToken'])
            ->setMetadata(['update', 'updateMobile', 'updateImages'])
            ->get();
    }

    /**
     *
     */
    private function showJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setData(['courier' => []])
            ->setMetadata(['update', 'updateMobile', 'updateImages'])
            ->get();
    }

    /**
     *
     */
    private function updateJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setData(['courier' => []])
            ->setMetadata(['show', 'updateMobile', 'updateImages'])
            ->get();
    }

    /**
     *
     */
    private function updateMobileJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setData(['courier' => []])
            ->setMetadata(['show', 'update', 'updateImages'])
            ->get();
    }

    /**
     *
     */
    private function resetPasswordJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setData(['courier' => [], 'accessToken'])
            ->setMetadata(['login'])
            ->get();
    }

    /**
     *
     */
    private function updateImagesJsonResponse(): array
    {
        return (new JsonResponseBuilder())
            ->setData(['courier' => []])
            ->setMetadata(['show', 'update', 'updateMobile'])
            ->get();
    }
}
