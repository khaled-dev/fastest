<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'place_id' => $this->faker->uuid,
            'lat' => $this->faker->latitude,
            'lng' => $this->faker->longitude,
            'formatted_address' => $this->faker->address,
            'business_status' => $this->faker->state,
            'name' => $this->faker->domainName,
            'icon' => $this->faker->imageUrl(),
            'rating' => 3,
            'user_ratings_total' => 1,
        ];
    }
}
