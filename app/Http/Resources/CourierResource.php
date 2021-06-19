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
            'isActive' => (bool) $this->is_active,
            'hasAdminApproval' => (bool) $this->has_admin_approval,
            'city' => new CityResource($this->city),
            'nationality' => new NationalityResource($this->nationality),
            'bank' => new bankResource($this->bank),
            'carType' => new CarTypeResource($this->carType),
            'profileImage' => $this->profile_picture,
            'nationalCardImage' => $this->getFirstMediaUrl('national_card_image'),
            'carLicenseImage' => $this->getFirstMediaUrl('car_license_image'),
            'drivingLicenseImage' => $this->getFirstMediaUrl('driving_license_image'),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
