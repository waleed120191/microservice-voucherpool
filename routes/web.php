<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Voucher code
Route::post('/voucher_code/generate', 'VoucherCodeController@generate');
Route::post('/voucher_code/use', 'VoucherCodeController@use');

// User
Route::get('/user/all',['as' => 'userAll', 'uses' => 'UserController@all' ]);
Route::get('/user/emailExist', 'UserController@existByEmail');
Route::get('/user/voucherCode', 'VoucherCodeController@voucherByEmail');