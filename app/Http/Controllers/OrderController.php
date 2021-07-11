<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\Store;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Http\Response;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderStateCountResource;
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
     * View single order
     *
     * @param Order $order
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Order $order): Response
    {
        return $this->successResponse([
            'order' => new OrderResource($order)
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
        $order->addMultipleMediaToCollection($request->images, 'images');

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
        /** @var Courier $courier */
        $courier = auth()->user();

        return $this->successResponse([
            'ordersStateCount' => new OrderStateCountResource([
                'opened'           => Order::opened()->count(),
                'inProgress'       => $courier->offers()->accepted()->count(),
                'underNegotiation' => $courier->offers()->underNegotiation()->count(),
            ]),
        ]);
    }

    /**
     * Create new order for auth user
     *
     * @param string $state
     * @return OrderCollection|Response
     */
    public function listByState(string $state)
    {
        if (! in_array($state, Order::listStates())) {
            return $this->validationErrorResponse([
                "state" => [
                    "Invalid State Given."
                ]
            ]);
        }

        return new OrderCollection(Order::forGivenState($state)->desc()->paginate(10));
    }

    /**
     * List the delivery times set by the admin
     *
     * @return \Illuminate\Http\Response
     */
    public function deliveryTimeList(): Response
    {
        return $this->successResponse([
            'deliveryTimes' => Setting::all()->first()->delivery_time ?? []
        ]);
    }

    /**
     * Cancel the given order
     *
     * @param Order $order
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function cancel(Order $order): Response
    {
        $this->authorize('cancel', $order);

        $order->markAsCanceled();

        return $this->successResponse([
            'order' => new OrderResource($order->refresh())
        ]);
    }
}
