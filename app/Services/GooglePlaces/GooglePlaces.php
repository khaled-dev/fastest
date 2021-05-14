<?php


namespace App\Services\GooglePlaces;

use Illuminate\Support\Collection;
use SKAgarwal\GoogleApi\PlacesApi;
use App\Services\contracts\IGooglePlaces;

class GooglePlaces implements IGooglePlaces
{
    /**
     * Places types.
     *
     * @var array
     */
    private array $types;

    /**
     * Google places key.
     *
     * @var string
     */
    private string $googleKey;

    /**
     * Regions of the places.
     *
     * @var string
     */
    private string $region;

    /**
     * un-formatted places.
     *
     * @var array
     */
    private array $places;

    /**
     * formatted places.
     *
     * @var \Illuminate\Support\Collection
     */
    private $formattedPlaces = null;

    /**
     * GooglePlaces constructor.
     */
    public function __construct()
    {
        $this->region = 'sa';
        $this->googleKey = env('GOOGLE_PLACES_KEY', '');
    }

    /**
     * Set array of types for places.
     *
     * @param array|string|float|int $types
     * @return $this
     */
    public function setTypes($types): GooglePlaces
    {
        if (! is_array($types)) {
            $types = [
               (string) $types
            ];
        }

        $this->types = $types;

        return $this;
    }

    /**
     * Send request to google-places API to fetch the places.
     *
     * @return $this
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function importPlaces(): GooglePlaces
    {
        $googlePlaces = new PlacesApi($this->googleKey);
        $places = [];

        foreach ($this->types as $type) {

            $firstPage = $googlePlaces->textSearch(null, [
                'region' => $this->region,
                'type' => $type
            ]);

            $nextPageToken = $firstPage['next_page_token'] ?? null;

            $places[] = $firstPage;

            while ($nextPageToken) {
                sleep(2);

                $nextPage = $googlePlaces->textSearch(null, [
                    'pagetoken' => $nextPageToken,
                    'region' => $this->region,
                    'type' => $type
                ]);

                $nextPageToken = $nextPage['next_page_token'] ?? null;

                $places[] = $nextPage;
            }
        }

        $this->places = $places;

        return $this;
    }

    /**
     * Formats and returns the imported places.
     *
     * @return Collection
     */
    public function getPlaces(): Collection
    {
        $this->formattedPlaces = new Collection();

        foreach ($this->places as $places) {
            foreach ($places['results'] as $result) {
                $this->formattedPlaces->put(null , [
                    'place_id' => $result['place_id'],
                    'lat' => $result['geometry']['location']['lat'],
                    'lng' => $result['geometry']['location']['lng'],
                    'formatted_address' => $result['formatted_address'],
                    'business_status' => $result['business_status'],
                    'name' => $result['name'],
                    'icon' => $result['icon'],
                    'rating' => $result['rating'],
                    'user_ratings_total' => $result['user_ratings_total'],
                    'types' => $result['types'],
                ]);
            }
        }

        return $this->formattedPlaces->unique('place_id');
    }
}
