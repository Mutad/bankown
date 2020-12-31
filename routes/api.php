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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['as' => 'api.'], function () {
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('auth/user', function (Request $request) {
            return response()->json($request->user(), 200);
        });
        Route::resource('users', 'Api\UserController');
        Route::resource('cards', 'Api\CardController')->except(['create', 'edit']);
        Route::get('card/{number}/info', 'Api\CardController@showByNumber')->name('cards.info');
        Route::get('cards/{card}/transactions', 'Api\TransactionController@getTransactionsOfCard')->name('cards.transactions');
        Route::post('transaction', 'Api\TransactionController@createTransaction');
    });

    Route::get('status', function () {
        return response()->json(['message' => 'ok'], 200);
    })->name('status');

    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('login', 'Api\Auth\LoginController@login')->name('login');
        Route::post('register', 'Api\Auth\RegisterController@register')->name('register');
    });
});