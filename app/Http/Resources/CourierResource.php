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
            'name' => $this->name,
            'mobile' => $this->mobile,
            'national_number' => $this->national_number,
            'gender' => $this->gender,
            'car_number' => $this->car_number,
            'iban_number' => $this->iban_number,
            'is_active' => $this->is_active,
            'has_admin_approved' => $this->has_admin_approved,
            'territory' => $this->territory,
            'city' => $this->city,
            'nationality' => $this->nationality,
            'bank' => $this->bank,
            'car_type' => $this->car_type,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
