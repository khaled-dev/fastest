<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Returns the terms and conditions.
     *
     * @return array
     */
    public function termsAndConditions(): array
    {
        return [
            'terms_and_conditions' => Setting::first()->terms_and_conditions,
        ];
    }
}
