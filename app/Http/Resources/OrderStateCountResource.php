<?php

namespace App\Http\Resources;

use App\Models\Order;
use App\Models\Store;
use App\Models\Customer;
use App\Models\Location;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderStateCountResource extends JsonResource
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
            'openedCount' => $this->resource['opened'],
            'underNegotiationCount' => $this->resource['underNegotiation'],
            'inProgressCount' => $this->resource['inProgress'],
        ];
    }
}
