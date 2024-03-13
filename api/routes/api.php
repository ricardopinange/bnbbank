<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'user'], function () {
    Route::post('register', 'UserController@register');
});

Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group(['middleware' => ['auth:api']], function() {

    Route::group(['prefix' => 'transaction'], function () {
        Route::group(['middleware' => ['auth.customer']], function () {
            Route::get('list', 'TransactionController@index');
            Route::post('insert', 'TransactionController@store');
            Route::get('current-balance', 'TransactionController@currentBalance');
            Route::get('month-balance', 'TransactionController@monthBalance');
        });
    });

    Route::group(['prefix' => 'check'], function () {
        Route::group(['middleware' => ['auth.admin']], function () {
            Route::get('control', 'CheckController@control');
            Route::get('details/{id}', 'CheckController@details');
            Route::post('approve/{id}', 'CheckController@approve');
        });
        Route::group(['middleware' => ['auth.customer']], function () {
            Route::get('list', 'CheckController@index');
            Route::post('insert', 'CheckController@store');
        });
    });

});
