<?php namespace App\Esim\Battle;

use App\Esim\Esim;

class BattleList extends Esim {

    protected $table = 'battle_list';

    protected $fillable = ['server', 'battle_id', 'rounds'];

    public $timestamps = false;

}