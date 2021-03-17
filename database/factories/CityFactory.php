<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Territory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_ar' => $this->faker->city,
            'name_en' => $this->faker->city,
            'territory_id' => Territory::factory()->create()->id,
        ];
    }
}
