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


Route::group(
    ['prefix' => '{locale}',
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware'=>'locale'],
    function () {
        Route::get('/', function () {
            return view('welcome');
        })->name('welcome');


          
        Route::group(['prefix' => '/hub','middleware' => ['auth']], function () {
            Route::get('/', function () {
                return view('pages.hub.index');
            })->name('hub');
        });

        Route::group(['prefix' => '/legal'], function () {
            Route::get('terms', function () {
                return view('pages.legal.terms');
            })->name('terms_of_use');
            Route::get('privacy', function () {
                return view('pages.legal.privacy');
            })->name('privacy_policy');
        });
        
        Route::group(['prefix' => '/auth','as'=>'auth.'], function () {
            Route::get('login', 'Auth\LoginController@show')->name('login');
        
            Route::post('login', 'Auth\LoginController@login');
        
            Route::get('register', 'Auth\RegisterController@create')->name('register');
        
            Route::post('register', 'Auth\RegisterController@store');
            Route::post('forgot', 'Auth\LoginController@forgot')->name('forgot');
        });
        
        Route::get('contact', function () {
            return redirect('/');
        })->name('contact');
    }
);

Route::get('/', function () {
    return redirect(app()->getLocale());
});




// Route::get('/apps', function () {
//     return view('apps.index');
// });

// Route::get('/bankown', function () {
//     return view('apps.bankown');
// });

