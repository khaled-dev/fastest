<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourierResetPassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobile' => 'required|max:225',
            'new_password' => 'required|max:225|confirmed',
            'new_password_confirmation' => 'required|max:225',
            'fbToken' => 'sometimes|string',
        ];
    }
}
