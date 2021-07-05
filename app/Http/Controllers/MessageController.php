<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Message;
use App\Events\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\TopicResource;
use App\Http\Resources\MessageResource;

class MessageController extends Controller
{

    /**
     * Sends message in the offer room.
     *
     * @param Request $request
     * @param Offer $offer
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function send(Request $request, Offer $offer): Response
    {
        $this->authorize('send', [Message::class, $offer]);

        $request->validate(['body' => 'required|string']);

        $message = auth()->user()->messages()->create([
            'body' => $request->body,
            'offer_id' => $offer->id,
        ]);

        SendMessage::dispatch($message);

        return $this->successResponse([
            'topicName' => $offer->chatTopic(),
            'messages'  => MessageResource::collection($offer->messages)
        ]);
    }

    /**
     * Sends images as messages in the offer room.
     *
     * @param Request $request
     * @param Offer $offer
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function uploadImages(Request $request, Offer $offer): Response
    {
        $this->authorize('send', [Message::class, $offer]);

        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image',
        ]);

        $message = $request->user()->messages()->create(['offer_id' => $offer->id]);
        $message->addMultipleMediaToCollection($request->images, 'images');

        SendMessage::dispatch($message);

        return $this->successResponse([
            'topicName' => $offer->chatTopic(),
            'messages'  => MessageResource::collection($offer->messages)
        ]);
    }

    /**
     * Lists all message in the offer room.
     *
     * @param Offer $offer
     * @return Response
     */
    public function list(Offer $offer): Response
    {
        return $this->successResponse([
            'topicName' => $offer->chatTopic(),
            'messages'  => MessageResource::collection($offer->messages)
        ]);
    }

    /**
     * Lists all message in the offer room.
     *
     * @return Response
     */
    public function topics(): Response
    {
        $acceptedOffers = auth()->user()->offers()->accepted()->get() ?? [];

        return $this->successResponse([
            'topics' => TopicResource::collection($acceptedOffers),
        ]);
    }

}
