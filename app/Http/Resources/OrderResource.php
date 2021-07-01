<?php

namespace App\Http\Resources;

use App\Models\Order;
use App\Models\Store;
use App\Models\Customer;
use App\Models\Location;
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

        // TODO: fix images url
        /** @var Order $this */

        return [
            'id' => $this->id,
            'store' => new StoreResource(Store::find($this->store_id)),
            'customer' => new CustomerResource(Customer::find($this->customer_id)),
            'location' => new LocationResource(Location::find($this->location_id)),
            'state' => $this->state,
            'description' => $this->description,
            'deliveryTime' => $this->delivery_time,
            'minOfferPrice' => $this->min_offer_price,
            'maxOfferPrice' => $this->max_offer_price,
            'images' => $this->getMedia('images'),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
