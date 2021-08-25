<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Resources\LocationResource;
use App\Http\Requests\LocationStoreRequest;
use App\Models\Location;

class LocationController extends Controller
{

    /**
     * List customer locations
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var Customer $customer */
        $customer = auth()->user();

        return $this->successResponse([
            'locations' => LocationResource::collection($customer->locations)
        ], [
            'store' => route('locations.store'),
        ]);
    }

    /**
     * Create new location for auth user
     *
     * @param LocationStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationStoreRequest $request)
    {
        /** @var Customer $customer */
        $customer = auth()->user();

        $location =  $customer->locations()->create($request->all());

        return $this->successResponse([
            'location' => new LocationResource($location)
        ], [
            'index' => route('locations.index'),
        ]);
    }

    /**
     * Delete location for auth user
     *
     * @param Location $location
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Location $location)
    {
        $this->authorize('delete', $location);

        $location->delete();

        return $this->successResponse([
            'locations' => LocationResource::collection(auth()->user()->locations)
        ], [
            'index' => route('locations.index'),
        ]);
    }
}
