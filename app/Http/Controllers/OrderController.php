<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderImagesRequest;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Response;
use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderSearchRequest;

class OrderController extends Controller
{
    /**
     * Create new order for auth user
     *
     * @param OrderSearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderSearchRequest $request)
    {
        /** @var Customer $customer */
        $customer = auth()->user();

        $order =  $customer->orders()->create($request->all());

        return $this->successResponse([
            'order' => new OrderResource($order)
        ]);
    }

    /**
     * Update order's images
     *
     * @param StoreOrderImagesRequest $request
     * @param Order $order
     * @return Response
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function updateImages(StoreOrderImagesRequest $request, Order $order): Response
    {
        /** @var Customer $customer */
        $customer = auth()->user();

        $customer->addMediaFromRequest('images')
            ->toMediaCollection('images');

        return $this->successResponse([
            'order' => new OrderResource($order),
        ]);
    }
}
