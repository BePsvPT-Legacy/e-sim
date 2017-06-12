<?php

namespace App;

class MilitaryUnit extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['updated_at'];

    /**
     * Get military unit name.
     *
     * @param string $server
     * @param int $militaryUnitId
     *
     * @return null|string
     */
    public static function name($server, $militaryUnitId)
    {
        $mu = self::where('server', $server)
            ->where('military_unit_id', $militaryUnitId)
            ->first();

        return $mu->name ?? null;
    }
}
