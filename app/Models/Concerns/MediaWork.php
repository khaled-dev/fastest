<?php


namespace App\Models\Concerns;


trait MediaWork
{

    /**
     * Add multiple media from the given request array to a given collection.
     *
     * @param array $mediaArray
     * @param string $collection
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function addMultipleMediaToCollection(array $mediaArray, string $collection)
    {
        foreach ($mediaArray as $media) {
            $this->addMedia($media)->toMediaCollection($collection);
        }
    }

    /**
     * Get all media files From given collection.
     *
     * @param string $collection
     * @return \Illuminate\Support\Collection|\Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection
     */
    public function getAllMediaFromCollection(string $collection)
    {
        return $this->getMedia($collection)->map(function ($mediaFile) {
            return $mediaFile->getUrl();
        });
    }

}
