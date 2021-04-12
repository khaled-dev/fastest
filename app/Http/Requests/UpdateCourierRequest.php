<?php

namespace App\Http\Requests;

use App\Models\Courier;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourierRequest extends FormRequest
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
            'territory_id' => 'required|exists:territories,id',
            'city_id' => 'required|exists:cities,id',
            'nationality_id' => 'required|exists:nationalities,id',
            'bank_id' => 'required|exists:banks,id',
            'name' => 'required|max:225',
            'new_password' => 'sometimes|max:225|confirmed',
            'new_password_confirmation' => 'sometimes|max:225',
            'gender' => 'required|in:male,female',
            'car_number' => 'required|max:225',
            'national_number' => [
                'required',
                'max:225',
                Rule::unique('couriers')->ignore($this->user()->id ?? '')
            ],
            'iban' => [
                'required',
                'max:225',
                Rule::unique('couriers')->ignore($this->user()->id ?? '')
            ],
        ];
    }
}
