<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Courier;
use App\Models\Customer;
use Illuminate\Http\Response;
use App\Http\Resources\OrderResource;
use App\Services\Logic\DistanceService;
use App\Http\Resources\OrderCollection;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StoresListInRangeRequest;
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
     * Show order's three states for courier
     *
     * @return Response
     */
    public function ordersStateCountsForCourier(): Response
    {
        /** @var Courier $courier */
        $courier = auth()->user();

        return $this->successResponse([
            'ordersStateCount' => new OrderStateCountResource([
                'opened'           => Order::opened()->count() + Order::underNegotiation()->count(),
                'inProgress'       => $courier->offers()->accepted()->count(),
                'underNegotiation' => $courier->offers()->underNegotiation()->count(),
            ]),
        ]);
    }

    /**
     * Show order's three states for customer
     *
     * @return Response
     */
    public function ordersStateCountsForCustomer(): Response
    {
        /** @var Customer $customer */
        $customer = auth()->user();

        return $this->successResponse([
            'ordersStateCount' => new OrderStateCountResource([
                'opened'           => Order::opened()->count(),
                'inProgress'       => $customer->offers()->accepted()->count(),
                'underNegotiation' => $customer->offers()->underNegotiation()->count(),
            ]),
        ]);
    }

    /**
     * List orders for customer by given state
     *
     * @param string $state
     * @return OrderCollection|Response
     */
    public function listByStateForCustomer(string $state)
    {
        /** @var Customer $customer */
        $customer = auth()->user();

        if (! in_array($state, Order::listStates())) {
            return $this->validationErrorResponse([
                "state" => [
                    "Invalid State Given."
                ]
            ]);
        }

        return new OrderCollection($customer->orders()->forGivenState($state)->desc()->paginate(10));
    }

    /**
     * List orders for courier by given state
     *
     * @param string $state
     * @param StoresListInRangeRequest $request
     * @return OrderCollection|Response
     */
    public function listByStateForCourier(string $state, StoresListInRangeRequest $request)
    {
        /** @var Courier $courier */
        $courier = auth()->user();

        if (! in_array($state, Order::listStates())) {
            return $this->validationErrorResponse([
                "state" => [
                    "Invalid State Given."
                ]
            ]);
        }

        if ($state == Order::OPENED) {
            $range = DistanceService::range($request->lat, $request->lng);

            return new OrderCollection(
                Order::forGivenStates([Order::OPENED, Order::UNDER_NEGOTIATION])->desc()->storeInGivenRange($range)->paginate(10)
            );
        }

        return new OrderCollection($courier->orders()->forGivenState($state)->desc()->paginate(10));
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
