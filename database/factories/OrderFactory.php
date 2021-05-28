<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Location;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'store_id' => Store::factory()->create()->id,
            'customer_id' => Customer::factory()->create()->id,
            'location_id' => Location::factory()->create()->id,
            'description' => $this->faker->realText(),
            'min_offer_price' => $this->faker->randomFloat(),
            'max_offer_price' => $this->faker->randomFloat(),
        ];
    }
}
