<?php

namespace App\Http\Requests;

use App\Models\Courier;
use App\Rules\Mobile;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourierMobileRequest extends FormRequest
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
            'fb_token' => 'required|string',
            'mobile' => [
                'required',
                'max:225',
                new Mobile,
                Rule::unique('couriers')->ignore($this->user()->id ?? '')
            ],
        ];
    }
}
