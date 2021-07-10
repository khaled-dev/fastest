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
        return [
            'data' => [
                'topicName' => $request->offer->chatTopic(),
                'offer'     => new OfferResource($request->offer),
                'messages'  => $this->collection,
            ]
        ];
    }
}
