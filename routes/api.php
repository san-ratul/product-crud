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

Route::post('/login', 'Api\UserController@login');
Route::post('/register', 'Api\UserController@register');
Route::get('/products', 'Api\ProductController@index');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', 'Api\UserController@logout');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/product/store', 'Api\ProductController@store');
    Route::delete('/product/{product}', 'Api\ProductController@distroy');
    Route::patch('/product/edit/{product}', 'Api\ProductController@update');
});
