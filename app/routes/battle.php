<?php

Route::get('/', [
    'as' => 'index',
    'uses' => 'HomeController@index'
]);

Route::group(['prefix' => '{server}', 'before' => 'server'], function($server) {

    Route::get('/', [
        'uses' => 'HomeController@index'
    ]);

    Route::group(['prefix' => 'battle'], function() {

        Route::get('/', [
            'as' => 'battle.index',
            'uses' => 'BattleController@index'
        ]);

        Route::group(['prefix' => '{id}', 'before' => 'battle'], function($id) {

            Route::get('/', [
                'as' => 'battle.all',
                'uses' => 'BattleController@battle'
            ]);

            Route::get('mu', [
                'as' => 'battle.mu',
                'uses' => 'BattleController@battle_mu'
            ]);

            Route::get('{round}', [
                'before' => 'battle_round',
                'as' => 'battle.round',
                'uses' => 'BattleController@battle'
            ]);

        });

    });

});