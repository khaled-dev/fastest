<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            'messages' => MessageResource::collection($offer->messages)
        ]);
    }

    public function uploadImage()
    {

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
            'messages' => MessageResource::collection($offer->messages)
        ]);
    }

}
