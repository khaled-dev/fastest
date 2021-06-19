<?php

namespace App\Http\Requests;

use App\Rules\Mobile;
use Illuminate\Foundation\Http\FormRequest;

class LoginCustomerRequest extends FormRequest
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
            'mobile' => ['required', 'max:225', new Mobile],
            'fb_token' => 'required|string',
            'fb_registration_token' => 'required|string',
        ];
    }
}
