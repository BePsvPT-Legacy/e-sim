<?php

namespace App;

class Parameter extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'double',
    ];

    /**
     * Get the user's first name.
     *
     * @return bool
     */
    public function getPercentageAttribute()
    {
        static $percentages = [
            'Reduce Miss Chance',
            'Increase Critical Chance',
            'Increase Maximum Damage',
            'Increase Damage',
            'Increase Chance to Avoid Damage',
            'Increase Chance for Free Flight',
            'Increase Chance to Use Less Weapons for Berserk',
            'Increase Chance to Find a Weapon',
        ];

        return in_array($this->type, $percentages);
    }
}
