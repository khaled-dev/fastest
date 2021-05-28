<?php

namespace App\Http\Requests;

use App\Models\Courier;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderImagesRequest extends FormRequest
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
            'images' => 'required',
            'images.*' => 'image',
        ];
    }
}
