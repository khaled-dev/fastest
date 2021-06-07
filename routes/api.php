<?php

use App\Jobs\ImportPlacesFromGoogle;
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
Route::post('/couriers', 'CourierController@store')->name('couriers.store');
Route::post('/couriers-login', 'CourierController@login')->name('couriers.login');
Route::put('/couriers-reset-password', 'CourierController@resetPassword')->name('couriers.reset_password');
Route::post('/customers-login', 'CustomerController@registerOrLogin')->name('customers.login');

// Items
Route::get('/banks', 'ItemsController@banks');
Route::get('/car-types', 'ItemsController@carTypes');
Route::get('/nationalities', 'ItemsController@nationalities');
Route::get('/territories', 'ItemsController@territories');
Route::get('/cities/{territory}', 'ItemsController@cities');
Route::get('/terms-and-conditions', 'SettingController@termsAndConditions');

// Customer
Route::middleware('auth:customers')->group(function () {
    Route::get('/customers', 'CustomerController@show')->name('customers.show');
    Route::put('/customers/update', 'CustomerController@update')->name('customers.update');
    Route::post('/customers/update-images', 'CustomerController@updateImages')->name('customers.update_images');
    Route::get('/locations', 'LocationController@index')->name('locations.index');
    Route::post('/locations', 'LocationController@store')->name('locations.store');
    Route::post('/stores/{store}/orders', 'OrderController@store')->name('orders.store');
    Route::post('/orders/{order}/images', 'OrderController@updateImages')->name('orders.update_images');
    Route::get('/orders/state-counts', 'OrderController@ordersStateCounts')->name('orders.orders_state_counts');
    Route::get('/orders/{state}', 'OrderController@listByState')->name('orders.by_state');
    Route::get('/orders/{order}/cancel', 'OrderController@cancel')->name('orders.cancel');
    Route::get('/orders/{order}/offers', 'OfferController@index')->name('offers.index');
    Route::get('/offers/{offer}', 'OfferController@show')->name('offers.show');
    Route::put('/offers/{offer}/accept', 'OfferController@accept')->name('offers.accept');
});

Route::post('/stores/search', 'StoreController@search')->name('stores.search');
Route::post('/stores/nearby', 'StoreController@nearby')->name('stores.nearby');
Route::post('/stores/{store}', 'StoreController@show')->name('stores.show');

// Courier
Route::middleware('auth:couriers')->group(function () {
    Route::get('/couriers', 'CourierController@show')->name('couriers.show');
    Route::put('/couriers/update', 'CourierController@update')->name('couriers.update');
    Route::put('/couriers/update-mobile', 'CourierController@updateMobile')->name('couriers.update_mobile');
    Route::post('/couriers/update-request', 'CourierController@storeUpdateRequest')->name('couriers.update_request');
    Route::post('/couriers/update-images', 'CourierController@updateImages')->name('couriers.update_images');
    Route::post('/orders/{order}/offers', 'OfferController@store')->name('offers.store');
});

Route::get('test', function () {
    ImportPlacesFromGoogle::dispatch();
});
