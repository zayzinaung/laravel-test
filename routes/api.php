<?php

use Illuminate\Http\Request;

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

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

// Route::group(['middleware' => 'auth:api'], function(){
	Route::get('user', 'API\UserController@user');
	Route::post('user/transaction', 'API\UserController@user_transcation');

	Route::group(['middleware' => 'admin'], function(){
		Route::post('user/wallet/create', 'API\UserController@user_create_wallet');
		Route::post('user/wallet/delete', 'API\UserController@user_delete_wallet');
		Route::post('user/wallet/detail', 'API\UserController@user_wallet_details');
	});

// });
