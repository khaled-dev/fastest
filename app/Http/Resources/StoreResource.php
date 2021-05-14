<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'placeId' => $this->place_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'formattedAddress' => $this->formatted_address,
            'name' => $this->name,
            'icon' => $this->icon,
            'rating' => $this->rating,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
