<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Resources\StoreResource;

class StoreController extends Controller
{
    /**
     * Search for a store by name
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // TODO: add mobile location
        $request->validate([
            'name' => 'required|string|max:225'
        ]);

        // TODO: limit by city
        $stores = Store::where('name', 'like', "%{$request->name}%")->get();

        return $this->successResponse([
            'stores' => StoreResource::collection($stores),
        ]);
    }
}
