<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourierResource extends JsonResource
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
            'territory_id' => $this->territory_id,
            'city_id' => $this->city_id,
            'nationality_id' => $this->nationality_id,
            'bank_id' => $this->bank_id,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'national_number' => $this->national_number,
            'gender' => $this->gender,
            'car_number' => $this->car_number,
            'iban_number' => $this->iban_number,
            'is_active' => $this->is_active,
            'has_admin_approved' => $this->has_admin_approved,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
