<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Rules\Mobile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // must be customer to use this request
        return $this->user() instanceof Customer;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:225',
            'mobile' => [
                'required',
                'max:225',
                new Mobile,
                Rule::unique('customers')->ignore($this->user()->id ?? '')
            ],
        ];
    }
}
