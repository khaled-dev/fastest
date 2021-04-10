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
            'iban' => $this->iban,
            'is_active' => $this->is_active,
            'has_admin_approval' => $this->has_admin_approval,
            'territory' => $this->territory,
            'city' => $this->city,
            'nationality' => $this->nationality,
            'bank' => $this->bank,
            'profile_picture' => $this->getFirstMediaUrl('profile_picture'),
            'national_card_picture' => $this->getFirstMediaUrl('national_card_picture'),
            'car_license_picture' => $this->getFirstMediaUrl('car_license_picture'),
            'driving_license_picture' => $this->getFirstMediaUrl('driving_license_picture'),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
