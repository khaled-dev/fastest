<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $message = $this->collection->first();

        return [
            'data' => [
                'topicName' => empty($message) ? null : $message->offer->chatTopic(),
                'offer'     => empty($message) ? null : new OfferResource($message->offer),
                'messages'  => $this->collection,
            ]
        ];
    }
}
