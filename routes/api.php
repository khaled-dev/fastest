<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ->middleware('auth:api')
Route::post('/customer-login', 'CustomerController@registerOrLogin');

Route::middleware('auth:customers')->group(function () {
    Route::get('/customer', 'CustomerController@show');
    Route::PUT('/customer/update', 'CustomerController@update');
});
