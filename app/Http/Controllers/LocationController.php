<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Resources\LocationResource;
use App\Http\Requests\LocationStoreRequest;

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
}
