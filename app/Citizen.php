<?php

namespace App;

class Citizen extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['updated_at'];

    /**
     * Get citizen name.
     *
     * @param string $server
     * @param int $citizenId
     *
     * @return null|string
     */
    public static function name($server, $citizenId)
    {
        $citizen = self::where('server', $server)
            ->where('citizen_id', $citizenId)
            ->first();

        if (is_null($citizen)) {
            return null;
        }

        return $citizen->name;
    }
}
