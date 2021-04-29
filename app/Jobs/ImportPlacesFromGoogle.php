<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use SKAgarwal\GoogleApi\PlacesApi;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
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
        // use env variable
        // api-key AIzaSyDNfwbrYEUHTk5PXcCH3gpgmqJNqfpFF5o
        $googlePlaces = new PlacesApi('AIzaSyDNfwbrYEUHTk5PXcCH3gpgmqJNqfpFF5o');
        // make region list
        // SA-01
        // SA-14
        $test  = $googlePlaces->textSearch('', ['region' => 'SA-01']);

        dd($test);
    }
}
