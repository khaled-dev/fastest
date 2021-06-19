<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

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
        $order = $this->order;
        $minPrice = $order->min_offer_price ?? 0;
        $maxPrice = $order->max_offer_price ?? 100;

        return [
            'price' => "required|numeric|between:{$minPrice},{$maxPrice}",
            'delivery_time' => 'required|string|max:255',
        ];
    }
}
