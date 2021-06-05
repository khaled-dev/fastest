<?php

namespace App\Http\Controllers;

use App\Events\OfferAccepted;
use App\Models\Offer;
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

    /**
     * List all Offers
     *
     * @param Order $order
     * @return Response
     */
    public function index(Order $order): Response
    {
        return $this->successResponse([
            'offers' => OfferResource::collection($order->offers()->get())
        ]);
    }

    /**
     * Show one Offer
     *
     * @param Offer $offer
     * @return Response
     */
    public function show(Offer $offer): Response
    {
        return $this->successResponse([
            'offer' => new OfferResource($offer)
        ]);
    }

    /**
     * Accept given offer.
     *
     * @param Offer $offer
     * @return Response
     */
    public function accept(Offer $offer): Response
    {
        $offer->accept();

        OfferAccepted::dispatch($offer);

        $offer->order->inProgress();

        // TODO: open chat

        return $this->successResponse([
            'offer' => new OfferResource($offer->refresh())
        ]);
    }
}
