<?php

namespace App\Http\Requests;

use App\Models\Courier;
use Illuminate\Foundation\Http\FormRequest;

class UpdateImagesCourierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // must be courier to use this request
        return $this->user() instanceof Courier;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'profile_picture' => 'sometimes|image',
            'national_card_picture' => 'sometimes|image',
            'car_form_picture' => 'sometimes|image',
            'driving_license_picture' => 'sometimes|image',
        ];
    }
}
