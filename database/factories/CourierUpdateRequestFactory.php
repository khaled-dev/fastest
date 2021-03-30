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
        $territory_id = Territory::first()->id;

        return [
            'courier_id'      => Courier::factory()->create()->id,
            'territory_id'    => $territory_id ?? null,
            'city_id'         => $territory_id ? City::forTerritoryId($territory_id)->first()->id : null,
            'car_type_id'     => CarType::first()->id,
            'nationality_id'  => Nationality::first()->id,
            'bank_id'         => Bank::first()->id,
            'name'            => $this->faker->name,
            'national_number' => $this->faker->randomNumber(9),
            'gender'          => 'male',
            'car_number'      => $this->faker->randomNumber(4),
            'iban_number'     => $this->faker->randomNumber(6),
        ];
    }
}
