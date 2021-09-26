<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Order;
use App\Models\Courier;
use App\Events\OfferPlaced;
use Illuminate\Http\Response;
use App\Events\OfferRejected;
use App\Events\OfferAccepted;
use App\Events\OfferCanceled;
use App\Events\OfferCompleted;
use App\Events\RequestCancellation;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OfferCollection;
use App\Http\Requests\StoreOfferRequest;
use App\Events\OfferRejectCancellationRequest;

class OfferController extends Controller
{

    /**
     * Store new Offer
     *
     * @param StoreOfferRequest $request
     * @param Order $order
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreOfferRequest $request, Order $order): Response
    {
        $this->authorize('store', [Offer::class, $order]);

        /** @var Courier $courier */
        $courier = auth()->user();

        $offer = $courier->offers()->create(
            array_merge($request->all(), ['order_id' => $order->id])
        );

        OfferPlaced::dispatch($offer);

        return $this->successResponse([
            'offer' => new OfferResource($offer->refresh())
        ]);
    }

    /**
     * List all Offers
     *
     * @param Order $order
     * @return OfferCollection
     */
    public function index(Order $order): OfferCollection
    {
        return new OfferCollection($order->offers()->desc()->paginate(10));
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function accept(Offer $offer): Response
    {
        $this->authorize('accept', $offer);

        $offer->markAsAccepted();

        $offer->order->markAsInProgress();

        OfferAccepted::dispatch($offer);

        return $this->successResponse([
            'offer' => new OfferResource($offer->refresh())
        ]);
    }

    /**
     * Cancel given offer.
     *
     * @param Offer $offer
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function cancel(Offer $offer): Response
    {
        $this->authorize('cancel', $offer);

        if ($offer->hasCancelTimePassed() && ! $offer->hasCancellationRequest()) {
            RequestCancellation::dispatch($offer);

            $offer->requestCancellation(auth()->user());

            return $this->successResponse([
                'offer' => new OfferResource($offer->refresh())
            ]);
        }

        $offer->markAsCanceled();


        OfferCanceled::dispatch($offer);

        return $this->successResponse([
            'offer' => new OfferResource($offer->refresh())
        ]);
    }

    /**
     * Reject cancellation request for given offer.
     *
     * @param Offer $offer
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function rejectCancellationRequest(Offer $offer): Response
    {
        $this->authorize('rejectCancellationRequest', $offer);

        $offer->rejectCancellationRequest();

        OfferRejectCancellationRequest::dispatch($offer);

        return $this->successResponse([
            'offer' => new OfferResource($offer->refresh())
        ]);
    }

    /**
     * Reject given offer.
     *
     * @param Offer $offer
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reject(Offer $offer): Response
    {
        $this->authorize('reject', $offer);

        $offer->markAsRejected();

        OfferRejected::dispatch($offer);

        return $this->successResponse([
            'offer' => new OfferResource($offer->refresh())
        ]);
    }

    /**
     * Complete given offer.
     *
     * @param Offer $offer
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function complete(Offer $offer): Response
    {
        $this->authorize('complete', $offer);

        $offer->markAsCompleted();

        $offer->order->markAsCompleted();

        OfferCompleted::dispatch($offer);

        return $this->successResponse([
            'offer' => new OfferResource($offer->refresh())
        ]);
    }
}
