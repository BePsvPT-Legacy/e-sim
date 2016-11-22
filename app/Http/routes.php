<?php

get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::group(['prefix' => '{server}', 'middleware' => 'game.server'], function()
{
    Route::group(['prefix' => 'battle/{id}'], function()
    {
        get('/', ['as' => 'battle.entire', 'uses' => 'BattleController@entire']);
        get('mu', ['as' => 'battle.mu', 'uses' => 'BattleController@mu']);
        get('{round}', ['as' => 'battle.round', 'uses' => 'BattleController@round']);
    });

    get('live/{id}', ['as' => 'battle.live', 'uses' => 'BattleController@live']);
});