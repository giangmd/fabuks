<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', ['uses' => 'HomeController@index', 'as' => 'user.home']);

Route::group(['middleware' => ['check_permisson:admin'], 'prefix' => 'fabuks', 'namespace' => 'Admin', 'as' => 'admin.'], function() {

    Route::get('/', ['uses' => 'DashboardController@index', 'as' => 'dashboard']);
    Route::get('/user', ['uses' => 'DashboardController@userList', 'as' => 'user']);

    Route::get('/user/{id}/edit', ['uses' => 'DashboardController@userEdit', 'as' => 'user.edit']);
    Route::post('/user/{id}/edit', ['uses' => 'DashboardController@userUpdate', 'as' => 'user.edit']);

    Route::get('/user/{id}/offers', ['uses' => 'DashboardController@userOffers', 'as' => 'user.offers']);

});

Auth::routes();
