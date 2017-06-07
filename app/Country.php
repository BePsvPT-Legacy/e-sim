<?php

namespace App;

class Country extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['updated_at'];

    /**
     * Get the country code.
     *
     * @param string $value
     * @return string
     */
    public function getCodeAttribute($value)
    {
        static $mapping = [
            'a' => 'au',
            'i' => 'ie',
            'ger' => 'de',
        ];

        return $mapping[$value] ?? $value;
    }
}
