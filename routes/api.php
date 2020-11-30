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

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('auth/user', function (Request $request) {
        return response()->json($request->user(), 200);
    });
    Route::resource('user', 'Api\UserController');
    Route::resource('card', 'Api\CardController')->except(['create','edit']);
    Route::get('card/info/{number}','Api\CardController@showByNumber');
    Route::post('transaction','Api\TransactionController@createTransaction');
});

Route::get('status', function () {
    return response()->json(['message'=>'ok'], 200);
});
Route::post('auth/login', 'Auth\LoginController@login');
Route::post('auth/register','Auth\RegisterController@register');