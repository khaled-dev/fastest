<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
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
        $deliveryTimes = Setting::all()->first()->delivery_time;

        return [
            'location_id' => 'required|exists:locations,id',
            'description' => 'required|string',
            'delivery_time' => [
              'required',
               'string',
               Rule::in($deliveryTimes ?? [])
            ]
        ];
    }
}
