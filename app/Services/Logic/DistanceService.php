<?php


namespace App\Services\Logic;


use App\Models\Setting;

class DistanceService
{
    /**
     * Returns the available range.
     *
     * @param float $lat
     * @param float $lng
     * @return array
     */
    public static function range(float $lat,float $lng): array
    {
        $searchRange = Setting::first()->search_range;
        $searchRange = $searchRange/100;

        return [
            'latRange' => [$lat - (111 * $searchRange), $lat + (111 * $searchRange)],
            'lngRange' => [$lng - (111 * $searchRange), $lng + (111 * $searchRange)],
        ];
    }
}
