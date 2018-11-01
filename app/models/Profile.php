<?php

class Profile extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_profiles';

    /**
     * Fillable fields.
     *
     * @var array
     */
    protected $fillable = ['phone_number'];

    /**
     * Associated User.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('User');
    }
}
