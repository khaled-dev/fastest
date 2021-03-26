<?php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\CarType;
use App\Models\City;
use App\Models\Courier;
use App\Models\Nationality;
use App\Models\Territory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Courier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $territory_id = Territory::first()->id;
        return [
            'territory_id'       => $territory_id ?? null,
            'city_id'            => $territory_id ? City::forTerritoryId($territory_id)->first()->id : null,
            'car_type_id'        => CarType::first()->id,
            'nationality_id'     => Nationality::first()->id,
            'bank_id'            => Bank::first()->id,
            'name'               => $this->faker->name,
            'mobile'             => $this->faker->phoneNumber,
            'national_number'    => $this->faker->randomNumber(9),
            'gender'             => 'male',
            'car_number'         => $this->faker->randomNumber(4),
            'iban_number'        => $this->faker->randomNumber(6),
            'password'           => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'is_active'          => false,
            'has_admin_approved' => false,
        ];
    }
}
