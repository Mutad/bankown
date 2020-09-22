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

Route::get('/apps', function () {
    return view('apps.index');
});

Route::get('/bankown', function () {
    return view('apps.bankown');
});