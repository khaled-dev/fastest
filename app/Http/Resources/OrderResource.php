<?php

namespace App\Http\Resources;

use App\Models\Location;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'store' => new CustomerResource(Store::find($this->store_id)),
            'customer' => new CustomerResource(Order::find($this->customer_id)),
            'location' => new LocationResource(Location::find($this->location_id)),
            'state' => $this->state,
            'description' => $this->description,
            'minOfferPrice' => $this->min_offer_price,
            'maxOfferPrice' => $this->max_offer_price,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
