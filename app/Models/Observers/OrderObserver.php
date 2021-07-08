<?php

namespace App\Models\Observers;

use App\Models\Order;
use App\Models\Setting;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     *
     * @param  Order  $order
     * @return void
     */
    public function creating(Order $order)
    {
        $settings = Setting::all()->first();
        $minPricePerKmArray = json_decode($settings->min_price_per_km);
        rsort($minPricePerKmArray);
        $distance = $this->distance($order->location->lat, $order->location->lng, $order->store->lat, $order->store->lng);

        $price = $this->calculatePrice($distance, $minPricePerKmArray);

        $order->min_offer_price = $price;
        $order->max_offer_price = $settings->max_offer_price * $price;
    }

    /**
     * Calculate Min Price by Km category.
     *
     * @param $distance
     * @param $minPricePerKmArray
     * @return int
     */
    protected function calculatePrice($distance, $minPricePerKmArray): int
    {
        $price = 0;
        while ($distance > 0) {
            foreach ($minPricePerKmArray as $row) {
                if ($row->from <= $distance && $row->to >= $distance) {
                    $price += $row->price;
                    $distance--;
                }
            }
        }

        return $price;
    }

    /**
     * Get distance between two locations.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return int
     */
    protected function distance(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        $distance = (int) ($miles * 1.609344);

        return $distance < 1 ? 1 : $distance;
    }
}
