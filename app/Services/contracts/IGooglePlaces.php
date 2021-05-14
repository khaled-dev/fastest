<?php


namespace App\Services\contracts;

use Illuminate\Support\Collection;

interface IGooglePlaces
{
    /**
     * GooglePlaces constructor.
     */
    public function __construct();

    /**
     * Set array of types for places.
     *
     * @param array|string|float|int $types
     * @return $this
     */
    public function setTypes($types): IGooglePlaces;

    /**
     * Send request to google-places API to fetch the places.
     *
     * @return $this
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function importPlaces(): IGooglePlaces;

    /**
     * Formats and returns the imported places.
     *
     * @return Collection
     */
    public function getPlaces(): Collection;
}
