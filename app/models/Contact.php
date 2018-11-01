<?php

class Contact extends \Eloquent
{
    /**
     * @var string
     */
    protected $table = 'contacts';

    /**
     * @var array
     */
    protected $fillable = ['number'];

    /**
     * return associated group.
     *
     * @return mixed
     */
    public function group()
    {
        return $this->belongsTo('Group');
    }

    /**
     * return associated user.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('User');
    }
}
