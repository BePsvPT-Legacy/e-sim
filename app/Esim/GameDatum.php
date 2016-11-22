<?php namespace App\Esim;

class GameDatum extends Esim {

	public static function get_latest_battle_id($server_name)
    {
        $battle_id = GameDatum::where('name', '=', 'latest_battle_id')
            ->server($server_name)
            ->first();

        return $battle_id;
    }

}