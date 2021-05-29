<?php

namespace App\Http\Resources;

use App\Models\Order;
use App\Models\Courier;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'order' => new OrderResource(Order::find($this->order_id)),
            'courier' => new CourierResource(Courier::find($this->courier_id)),
            'price' => $this->price,
            'deliveryTime' => $this->delivery_time,
            'state' => $this->state,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
