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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '{server}'], function () {
    Route::group(['prefix' => 'battle/{id}', 'as' => 'battle.'], function () {
        Route::get('/', ['as' => 'entire', 'uses' => 'BattleController@entire']);
        Route::get('mu', ['as' => 'mu', 'uses' => 'BattleController@mu']);
        Route::get('{round}', ['as' => 'round', 'uses' => 'BattleController@round']);
    });
});
