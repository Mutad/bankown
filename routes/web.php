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

Route::post('/{token}/webhook', 'WebhookController@hook');

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '/hub','middleware' => ['auth']], function () {
    Route::get('', function () {
        return view('pages.hub.index');
    });
});


Route::get('/apps', function () {
    return view('apps.index');
});

Route::get('/bankown', function () {
    return view('apps.bankown');
});

Route::group(['prefix' => '/legal'], function () {
    Route::get('terms', function () {
        return view('pages.legal.terms');
    });
    Route::get('privacy', function () {
        return view('pages.legal.privacy');
    });
});

Route::group(['prefix' => '/auth'], function () {
    Route::get('login', function () {
        return view('pages.auth.login');
    })->name('login');

    Route::get('register', 'Auth\RegisterController@create')->name('register');

    Route::post('register', 'Auth\RegisterController@store')->name('register');
});
