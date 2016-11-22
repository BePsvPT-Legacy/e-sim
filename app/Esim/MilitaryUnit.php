<?php namespace App\Esim;

use App\Commands\CatchMilitaryUnitData;
use Illuminate\Support\Facades\Queue;

class MilitaryUnit extends Esim {

    protected $table = 'military_units';

    protected $fillable = ['server', 'name', 'mu_id'];

    public $timestamps = false;

    public static function get_mu_name($server_id = null, $mu_id = null)
    {
        if (is_null($server_id) || is_null($mu_id))
        {
            return null;
        }

        $mu = MilitaryUnit::where('mu_id', '=', $mu_id)
            ->server($server_id)
            ->first(['name']);

        if (is_null($mu) && 0 != $mu_id)
        {
            Queue::push(new CatchMilitaryUnitData($server_id, $mu_id));
        }

        return $mu;
    }

}