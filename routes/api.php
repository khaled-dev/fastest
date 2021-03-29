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
Route::post('/courier', 'CourierController@store');
Route::post('/courier-login', 'CourierController@login');
Route::put('/courier-reset-password', 'CourierController@resetPassword');
Route::post('/customer-login', 'CustomerController@registerOrLogin');

// Items
Route::get('/banks', 'ItemsController@banks');
Route::get('/car-types', 'ItemsController@carTypes');
Route::get('/nationalities', 'ItemsController@nationalities');
Route::get('/territories', 'ItemsController@territories');
Route::get('/cities/{territory}', 'ItemsController@cities');
Route::get('/terms-and-conditions', 'SettingController@termsAndConditions');

// Customer
Route::middleware('auth:customers')->group(function () {
    Route::get('/customer', 'CustomerController@show');
    Route::PUT('/customer/update', 'CustomerController@update');
});

// Courier
Route::middleware('auth:couriers')->group(function () {
    Route::get('/courier', 'CourierController@show');
    Route::PUT('/courier/update', 'CourierController@update');
    Route::post('/courier/update-images', 'CourierController@updateImages');
});
