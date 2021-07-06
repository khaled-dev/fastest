<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Setting;
use Illuminate\Http\Response;
use App\Http\Resources\StoreResource;
use App\Http\Requests\StoreNearbyRequest;
use App\Http\Requests\StoreSearchRequest;

class StoreController extends Controller
{
    /**
     * Search for a store by name.
     *
     * @param StoreSearchRequest $request
     * @return Response
     */
    public function search(StoreSearchRequest $request): Response
    {
        $range = $this->range($request->lat, $request->lng);

        $stores = Store::inRange($range['latRange'], $range['lngRange'])
            ->forNameLike($request->name)->get();

        return $this->successResponse([
            'stores' => StoreResource::collection($stores),
        ], [
            'nearby' => route('stores.nearby'),
        ]);
    }

    /**
     * Show a single store data
     *
     * @param StoreNearByRequest $request
     * @return Response
     */
    public function nearby(StoreNearbyRequest $request): Response
    {
        $range = $this->range($request->lat, $request->lng);

        $stores = Store::inRange($range['latRange'], $range['lngRange'])
            ->take(5)
            ->get();

        return $this->successResponse([
            'stores' => StoreResource::collection($stores),
        ], [
            'search' => route('stores.search'),
        ]);
    }

    /**
     * Show a single store data.
     *
     * @param Store $store
     * @return Response
     */
    public function show(Store $store): Response
    {
        return $this->successResponse([
            'store' => new StoreResource($store),
        ], [
            'nearby' => route('stores.nearby'),
            'search' => route('stores.search'),
        ]);
    }

    /**
     * Returns the available range.
     *
     * @param $lat
     * @param $lng
     * @return array
     */
    private function range($lat, $lng): array
    {
        $searchRange = Setting::first()->search_range;
        $searchRange = $searchRange/100;

        return [
            'latRange' => [$lat - (111 * $searchRange), $lat + (111 * $searchRange)],
            'lngRange' => [$lng - (111 * $searchRange), $lng + (111 * $searchRange)],
        ];
    }
}
