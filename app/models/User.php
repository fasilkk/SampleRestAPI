<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * Fillable fields 
	 *
	 * @var array
	 */
	protected $fillable = ['username','password','email','fname','lname','address','status'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    /**
     * @return string
     */

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * get reminder email
     * @return string
     */
    public function getReminderEmail()
    {

        return $this->email;
    }



    public function setPasswordAttribute($pass){

        $this->attributes['password'] = Hash::make($pass);

    }

    /**
     * get user favorite datas
     * @return mixed
     */
    public function favorite()
    {
        return $this->hasMany('Favorite');

    }


      /**
     * get user profile datas
     * @return mixed
     */
    public function profile()
    {

        return $this->hasOne('Profile');
    }

      /**
     * get user contact datas
     * @return mixed
     */
     public function contacts()
    {

        return $this->hasMany('Contact');
    }

      /**
     * get user group datas
     * @return mixed
     */
     public function groups()
    {

        return $this->hasMany('Group');
    }


}
