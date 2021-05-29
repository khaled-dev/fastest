<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Courier;
use Illuminate\Http\Response;
use App\Http\Resources\OfferResource;
use App\Http\Requests\StoreOfferRequest;

class OfferController extends Controller
{

    /**
     * Store new Offer
     *
     * @param StoreOfferRequest $request
     * @param Order $order
     * @return Response
     */
    public function store(StoreOfferRequest $request, Order $order): Response
    {
        /** @var Courier $courier */
        $courier = auth()->user();

        $offer = $courier->offers()->create(
            array_merge($request->all(), ['order_id' => $order->id])
        );

        return $this->successResponse([
            'offer' => new OfferResource($offer->refresh())
        ]);
    }
}
