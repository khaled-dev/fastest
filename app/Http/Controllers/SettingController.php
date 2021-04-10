<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Response;

class SettingController extends Controller
{
    /**
     * Returns the terms and conditions.
     *
     * @return Response
     */
    public function termsAndConditions(): Response
    {
        return $this->successResponse([
            'terms_and_conditions' => Setting::first()->terms_and_conditions,
        ]);
    }
}
