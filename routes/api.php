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

// Auth
Route::post('/couriers', 'CourierController@store');
Route::post('/couriers-login', 'CourierController@login');
Route::put('/couriers-reset-password', 'CourierController@resetPassword');
Route::post('/customers-login', 'CustomerController@registerOrLogin');

// Items
Route::get('/banks', 'ItemsController@banks');
Route::get('/car-types', 'ItemsController@carTypes');
Route::get('/nationalities', 'ItemsController@nationalities');
Route::get('/territories', 'ItemsController@territories');
Route::get('/cities/{territory}', 'ItemsController@cities');
Route::get('/terms-and-conditions', 'SettingController@termsAndConditions');

// Customer
Route::middleware('auth:customers')->group(function () {
    Route::get('/customers', 'CustomerController@show');
    Route::put('/customers/update', 'CustomerController@update');
    Route::post('/customers/update-images', 'CustomerController@updateImages');
});

// Courier
Route::middleware('auth:couriers')->group(function () {
    Route::get('/couriers', 'CourierController@show');
    Route::put('/couriers/update', 'CourierController@update');
    Route::post('/couriers/update-images', 'CourierController@updateImages');
});
