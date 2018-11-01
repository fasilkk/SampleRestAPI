<?php

class Favorite extends \Eloquent
{
    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * return associated user.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('user');
    }
}
