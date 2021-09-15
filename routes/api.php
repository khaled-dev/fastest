<?php

use App\Jobs\ImportPlacesFromGoogle;
use App\Services\Logic\NotificationService;
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
    Route::delete('/locations/{location}', 'LocationController@delete')->name('locations.delete');
    Route::post('/stores/{store}/orders', 'OrderController@store')->name('orders.store');
    Route::post('/orders/{order}/images', 'OrderController@updateImages')->name('orders.update_images');
    Route::get('/customers/orders/{state}/list', 'OrderController@listByStateForCustomer')->name('customers.orders.by_state');
    Route::get('/orders/{order}/cancel', 'OrderController@cancel')->name('orders.cancel');
    Route::get('/orders/{order}/offers', 'OfferController@index')->name('offers.index');
    Route::get('/offers/{offer}', 'OfferController@show')->name('offers.show');
    Route::put('/offers/{offer}/accept', 'OfferController@accept')->name('offers.accept');
    Route::put('/offers/{offer}/reject', 'OfferController@reject')->name('offers.reject');
    Route::get('/customers/orders/state/counts', 'OrderController@ordersStateCountsForCustomer')->name('orders.orders_state_counts_for_customer');
});

Route::post('/stores/search', 'StoreController@search')->name('stores.search');
Route::post('/stores/nearby', 'StoreController@nearby')->name('stores.nearby');
Route::post('/stores/{store}', 'StoreController@show')->name('stores.show');
Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');

// Courier
Route::middleware('auth:couriers')->group(function () {
    Route::get('/couriers', 'CourierController@show')->name('couriers.show');
    Route::put('/couriers/update', 'CourierController@update')->name('couriers.update');
    Route::put('/couriers/update-mobile', 'CourierController@updateMobile')->name('couriers.update_mobile');
    Route::post('/couriers/update-request', 'CourierController@storeUpdateRequest')->name('couriers.update_request');
    Route::post('/couriers/update-images', 'CourierController@updateImages')->name('couriers.update_images');
    Route::post('/couriers/orders/{state}/list', 'OrderController@listByStateForCourier')->name('couriers.orders.by_state');
    Route::post('/orders/{order}/offers', 'OfferController@store')->name('offers.store');
    Route::get('/couriers/orders/state/counts', 'OrderController@ordersStateCountsForCourier')->name('orders.orders_state_counts_for_courier');
    Route::put('/offers/{offer}/complete', 'OfferController@complete')->name('offers.complete');
});

// all users
Route::middleware('auth:couriers,customers')->group(function () {
    Route::get('/notifications', 'NotificationController@index')->name('notifications.index');
    Route::put('/notifications/{notification}/read', 'NotificationController@read')->name('notifications.read');
    Route::post('/notifications/token', 'NotificationController@storeNotificationToken')->name('notifications.store_token');
    Route::put('/offers/{offer}/cancel', 'OfferController@cancel')->name('offers.cancel');
    Route::post('/offers/{offer}/chat', 'MessageController@send')->name('offers.chat.send');
    Route::post('/offers/{offer}/chat-images', 'MessageController@uploadImages')->name('offers.chat_images.send');
    Route::get('/offers/{offer}/chat', 'MessageController@list')->name('offers.chat.list');
    Route::get('/order-delivery-times', 'OrderController@deliveryTimeList')->name('orders.deliveryTimeList');
    Route::get('/topics', 'MessageController@topics')->name('chat.topics');
});

//Route::get('test', function () {
//    $t = 'daSyLdv0SteR-aqJ2qlrHe:APA91bFPC8hLvC-VEGbSfIzJWTbdPnMZqcdxeGmOr3f6Yymc3ro-CXcAck-7dahibqg6xYJd3Z0Q4YswXglDg6HOoPAjSDCR0KDQ1rP_NQ91tc4xK8PrRj1kpyHG45LvInE-QT3bopAO';
//
//    NotificationService::validateRegistrationToken($t);
//
//    NotificationService::pushNotification($t, [
//        'title'     => __('notifications.offers.placed.title'),
//        'body'      => __('notifications.offers.placed.body'),
//        'image_url' => 'test',
//    ]);
//
//});
