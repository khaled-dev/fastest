<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\City;
use App\Models\CarType;
use App\Models\Territory;
use App\Models\Nationality;
use App\Http\Resources\CityResource;
use App\Http\Resources\bankResource;
use App\Http\Resources\CarTypeResource;
use App\Http\Resources\TerritoryResource;
use App\Http\Resources\NationalityResource;

class ItemsController extends Controller
{

    /**
     *
     * @return array
     */
    public function banks(): array
    {
        return [
            'banks' => BankResource::collection(Bank::all())
        ];
    }

    /**
     *
     * @return array
     */
    public function nationalities(): array
    {
        return [
            'nationalities' => NationalityResource::collection(Nationality::all())
        ];
    }

    /**
     *
     * @return array
     */
    public function carTypes(): array
    {
        return [
            'car_types' => CarTypeResource::collection(CarType::all())
        ];
    }

    /**
     *
     * @return array
     */
    public function territories(): array
    {
        return [
            'territories' => TerritoryResource::collection(Territory::all())
        ];
    }

    /**
     *
     * @param Territory $territory
     * @return array
     */
    public function cities(Territory $territory): array
    {
        return [
            'cities' => CityResource::collection(City::forTerritoryId($territory->id)->get())
        ];
    }
}
