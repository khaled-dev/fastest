<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\City;
use App\Models\CarType;
use App\Models\Territory;
use App\Models\Nationality;
use Illuminate\Http\Response;
use App\Http\Resources\CityResource;
use App\Http\Resources\bankResource;
use App\Http\Resources\CarTypeResource;
use App\Http\Resources\TerritoryResource;
use App\Http\Resources\NationalityResource;

class ItemsController extends Controller
{

    /**
     *
     * @return Response
     */
    public function banks(): Response
    {
        return $this->successResponse([
            'banks' => BankResource::collection(Bank::all())
        ]);
    }

    /**
     *
     * @return Response
     */
    public function nationalities(): Response
    {
        return $this->successResponse([
            'nationalities' => NationalityResource::collection(Nationality::all())
        ]);
    }

    /**
     *
     * @return Response
     */
    public function carTypes(): Response
    {
        return $this->successResponse([
            'carTypes' => CarTypeResource::collection(CarType::all())
        ]);
    }

    /**
     *
     * @return Response
     */
    public function territories(): Response
    {
        return $this->successResponse([
            'territories' => TerritoryResource::collection(Territory::all())
        ]);
    }

    /**
     *
     * @param Territory $territory
     * @return Response
     */
    public function cities(Territory $territory): Response
    {
        return $this->successResponse([
            'cities' => CityResource::collection(City::forTerritoryId($territory->id)->get())
        ]);
    }
}
