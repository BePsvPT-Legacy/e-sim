<?php namespace App\Esim;

use App\Commands\CatchCitizenData;
use Illuminate\Support\Facades\Queue;

class Citizen extends Esim {

    public $timestamps = false;

    public static function get_citizen_name($server_id = null, $citizen_id = null)
    {
        if (is_null($server_id) || is_null($citizen_id))
        {
            return null;
        }

        $citizen = Citizen::where('citizen_id', '=', $citizen_id)
            ->server($server_id)
            ->first(['name', 'organization']);

        if (is_null($citizen))
        {
            Queue::push(new CatchCitizenData($server_id, $citizen_id));
        }

        return $citizen;
    }

}