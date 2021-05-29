<?php

namespace Database\Factories;

use App\Models\Offer;
use App\Models\Order;
use App\Models\Courier;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Offer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => Order::factory()->create()->id,
            'courier_id' => Courier::factory()->create()->id,
            'delivery_time' => $this->faker->realText(),
            'price' => $this->faker->randomFloat(),
            'state' => Offer::UNDER_NEGOTIATION,
        ];
    }
}
