<?php

namespace App;

class Equipment extends Model
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['parameters'];

    /**
     * 裝備素質.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parameters()
    {
        return $this->belongsToMany(Parameter::class);
    }
}
