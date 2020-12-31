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

Route::get('lang/{lang}', function ($locale) {
    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->route('welcome');
});

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::group(['prefix' => '/app', 'as' => 'app.'], function () {
    Route::get('/{any?}', function () {
        return view('pages.spa');
    })->where('any', '.*')->name('');
});

Route::group(['prefix' => '/legal'], function () {
    Route::get('terms', function () {
        return view('pages.legal.terms');
    })->name('terms_of_use');
    Route::get('privacy', function () {
        return view('pages.legal.privacy');
    })->name('privacy_policy');
});

Route::redirect('login', '/app/login', 301)->name('login');

Route::group(['prefix' => '/app', 'as' => 'app.'], function () {
    Route::get('/{any?}', function ($id) {
        return view('pages.spa');
    })->where('any', '.*');
});

Route::get('contact', function () {
    return redirect('/');
})->name('contact');