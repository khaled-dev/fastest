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
            'nationalNumber' => $this->national_number,
            'gender' => $this->gender,
            'carNumber' => $this->car_number,
            'iban' => $this->iban,
            'isActive' => $this->is_active,
            'hasAdminApproval' => $this->has_admin_approval,
            'territory' => $this->territory,
            'city' => $this->city,
            'nationality' => $this->nationality,
            'bank' => $this->bank,
            'profilePicture' => $this->getFirstMediaUrl('profile_picture'),
            'nationalCardPicture' => $this->getFirstMediaUrl('national_card_picture'),
            'carLicensePicture' => $this->getFirstMediaUrl('car_license_picture'),
            'drivingLicensePicture' => $this->getFirstMediaUrl('driving_license_picture'),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
