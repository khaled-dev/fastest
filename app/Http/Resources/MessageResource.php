<?php

namespace App\Http\Resources;

use App\Models\Courier;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'id' => $this->id,
            'offer' => new OfferResource($this->offer),
            'sender' => ($this->sender instanceof Courier)
                ? new CourierResource($this->sender)
                : new CustomerResource($this->sender),
            'body' => $this->body,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
