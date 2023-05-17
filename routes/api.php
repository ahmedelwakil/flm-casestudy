<?php

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {

    /** Authentications Routes */
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login')->name('auth.login');

        Route::group(['middleware' => 'auth'], function () {
            Route::post('logout', 'AuthController@logout')->name('auth.logout');
            Route::post('refresh', 'AuthController@refresh')->name('auth.refresh');
        });
    });

    /** Promo Codes Routes */
    Route::group(['prefix' => 'promo-codes', 'middleware' => 'auth'], function () {
        Route::post('generate', 'PromoCodeController@generatePromoCode')->name('promo-codes.generate');
        Route::post('validate', 'PromoCodeController@validatePromoCode')->name('promo-codes.validate');
    });
});

