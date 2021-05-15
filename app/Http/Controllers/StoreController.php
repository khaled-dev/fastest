<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNearbyRequest;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Http\Resources\StoreResource;
use App\Http\Requests\StoreSearchRequest;

class StoreController extends Controller
{
    /**
     * Search for a store by name
     *
     * @param StoreSearchRequest $request
     * @return Response
     */
    public function search(StoreSearchRequest $request): Response
    {
        // TODO: limit by city|range
        $stores = Store::forNameLike($request->name)->get();

        return $this->successResponse([
            'stores' => StoreResource::collection($stores),
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
        // TODO: get first five in range
        $stores = Store::take(5)->get();

        return $this->successResponse([
            'stores' => StoreResource::collection($stores),
        ]);
    }

    /**
     * Show a single store data
     *
     * @param Store $store
     * @return Response
     */
    public function show(Store $store): Response
    {
        return $this->successResponse([
            'store' => new StoreResource($store),
        ]);
    }
}
