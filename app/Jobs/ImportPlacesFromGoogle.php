<?php

namespace App\Jobs;

use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\GooglePlaces\GooglePlaces;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportPlacesFromGoogle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function handle()
    {
        $places = (new GooglePlaces())
            ->setTypes([
                'accounting',
                'amusement_park',
                'aquarium',
                'art_gallery',
                'bakery',
                'beauty_salon',
                'book_store',
                'cafe',
                'clothing_store',
                'convenience_store',
                'drugstore',
                'electronics_store',
                'florist',
                'furniture_store',
                'gas_station',
                'hardware_store',
                'hospital',
                'jewelry_store',
                'laundry',
                'library',
                'light_rail_station',
                'locksmith',
                'meal_delivery',
                'meal_takeaway',
                'mosque',
                'pet_store',
                'pharmacy',
                'post_office',
                'restaurant',
                'school',
                'secondary_school',
                'shoe_store',
                'shopping_mall',
                'storage',
                'store',
                'supermarket',
            ])
            ->importPlaces()
            ->getPlaces();

        $existsPlaces = Store::whereIn('place_id', $places->pluck('place_id'))->get();

        if (! $existsPlaces->isEmpty()) {
            $places = $places->filter(function ($place) use ($existsPlaces) {
                return ! in_array($place['place_id'], $existsPlaces->pluck('place_id')->toArray());
            });
        }

        $places->each(function ($place) {
            Store::create($place);
        });
    }
}
