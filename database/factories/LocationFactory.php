<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->streetName,
            'lat' => $this->faker->latitude,
            'lng' => $this->faker->longitude,
            'customer_id' => Customer::factory()->create()->id,
        ];
    }
}
