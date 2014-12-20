<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {
	
	protected $fillable = array('name', 'email', 'password', 'date_of_birth', 'month_of_birth', 'year_of_birth', 'address', 'designation', 'gender', 'token', 'active', 'referal_id', 'arrange_group');

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password'); // , 'remember_token'
    
    public function cart()
    {
        $this->belongsTo('Cart');
    }

}
