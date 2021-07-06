<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfferRequest extends FormRequest
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
        /** @var Order $order */
        $order    = $this->order;
        $minPrice = $order->min_offer_price;
        $maxPrice = $order->max_offer_price;
        $deliveryTimes = Setting::all()->first()->delivery_time;

        return [
            'price' => "required|numeric|between:{$minPrice},{$maxPrice}",
            'delivery_time' => [
                'required',
                'string',
                Rule::in($deliveryTimes ?? [])
            ]
        ];
    }
}
