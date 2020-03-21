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

Route::group(['prefix' => 'v1', 'as' => 'api.'], function() {
    Route::get('/', function() {
        return response()->json(['status' => true, 'message' => 'API is working!']);
    })->name('index');

    Route::group(['middleware' => ['auth:api'], 'namespace' => 'Api'], function() {

        Route::get('/giang', function() {
            return response()->json(['me' => auth()->user()]);
        });

        Route::resource('rate', 'RateController');
        
        Route::group(['prefix' => 'trade', 'as' => 'trade.'], function() {
            Route::get('/', ['uses' => 'TradeHistoryController@index', 'as' => 'index']);

            Route::post('refetch', ['uses' => 'TradeHistoryController@refetch', 'as' => 'refetch']);
            Route::post('offer', ['uses' => 'TradeHistoryController@offer', 'as' => 'offer']);
        });
    });

});

