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

        return [
            'price' => "required|numeric|between:{$order->min_offer_price},{$order->max_offer_price}",
            'delivery_time' => 'required|string|max:255',
        ];
    }
}
