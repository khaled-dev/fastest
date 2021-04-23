<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Bank;
use App\Models\CarType;
use App\Models\Courier;
use App\Models\Territory;
use App\Models\Nationality;
use App\Models\CourierUpdateRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourierUpdateRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CourierUpdateRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $territory_id = Territory::first()->id ??
                        Territory::factory()->create()->id;

        $city_id      = City::forTerritoryId($territory_id)->first()->id ??
                        City::factory(['territory_id' => $territory_id])->create()->id;

        return [
            'courier_id'      => Courier::factory()->create()->id,
            'territory_id'    => $territory_id,
            'city_id'         => $city_id,
            'car_type_id'     => CarType::first()->id ?? CarType::factory()->create()->id,
            'nationality_id'  => Nationality::first()->id ?? Nationality::factory()->create()->id,
            'bank_id'         => Bank::first()->id ?? Bank::factory()->create()->id,
            'name'            => $this->faker->name,
            'national_number' => $this->faker->randomNumber(9),
            'gender'          => 'male',
            'car_number'      => $this->faker->randomNumber(4),
            'iban'     => $this->faker->randomNumber(6),
        ];
    }
}
