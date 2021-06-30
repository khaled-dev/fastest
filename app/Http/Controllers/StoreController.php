<?php

namespace App\Http\Controllers;

use App\Models\Store;
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
        // TODO the Five is dynamic
        return [
            'latRange' => [$lat - (111 * 5), $lat + (111 * 5)],
            'lngRange' => [$lng - (111 * 5), $lng + (111 * 5)],
        ];
    }
}
