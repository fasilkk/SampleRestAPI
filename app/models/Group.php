<?php

class Group extends \Eloquent {
	
	protected $table ="contact_groups";

	protected $fillable = [];

    public function user()
    {
        return $this->belongsTo('user');
    }


    public function contact()
    {
        return $this->hasMany('Contact');
    }


}