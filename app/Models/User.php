<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	protected $table = 'users';
    protected $fillable = ['username','password','tipo'];
    protected $hidden = ['password', 'remember_token'];
    protected $primaryKey = 'uid';
}
