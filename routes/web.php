<?php

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

Route::get('/', 'Controller@home')->name('home');

Route::group(['prefix' => '{server}'], function () {
    Route::group(['prefix' => 'battle/{id}', 'middleware' => ['battle'], 'as' => 'battle.'], function () {
        Route::get('/', 'BattleController@entire')->name('entire');
        Route::get('country', 'BattleController@country')->name('country');
        Route::get('mu', 'BattleController@mu')->name('mu');
        Route::get('{round}', 'BattleController@round')->name('round');
    });
});
