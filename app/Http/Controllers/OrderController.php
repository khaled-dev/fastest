<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderStateCountResource;
use App\Models\Store;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Response;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderImagesRequest;

class OrderController extends Controller
{
    /**
     * Create new order for auth user
     *
     * @param StoreOrderRequest $request
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request, Store $store)
    {
        /** @var Customer $customer */
        $customer = auth()->user();

        $order =  $customer->orders()->create(
            array_merge($request->all(), ['store_id' => $store->id])
        );

        return $this->successResponse([
            'order' => new OrderResource($order->refresh())
        ]);
    }

    /**
     * Update order's images
     *
     * @param UpdateOrderImagesRequest $request
     * @param Order $order
     * @return Response
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function updateImages(UpdateOrderImagesRequest $request, Order $order): Response
    {
        $order->addMediaFromRequest('images')
            ->toMediaCollection('images');

        return $this->successResponse([
            'order' => new OrderResource($order),
        ]);
    }

    /**
     * Show order's three states
     *
     * @return Response
     */
    public function ordersStateCounts(): Response
    {
        return $this->successResponse([
            'ordersStateCount' => new OrderStateCountResource([
                'opened' => Order::opened()->count(),
                'underNegotiation' => Order::underNegotiation()->count(),
                'inProgress' => Order::inProgress()->count(),
            ]),
        ]);
    }

}
