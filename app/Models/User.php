<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    
	protected $table = 'users';
    protected $fillable = ['username','password','type'];
    protected $hidden = ['password'];
    protected $primaryKey = 'uid';

    public function scopeAllOrderByUsername($query)
    {
        return $query->orderBy('username', 'ASC')
                        ->get();
    }

    public static function CheckIfExists($username)
    {
        return DB::table('users')
                        ->where('username', $username)
                        ->exists();
    }


    public function getRememberToken()
    {
        return null; // not supported
    }

    public function setRememberToken($value)
    {
    // not supported
    }

    public function getRememberTokenName()
    {
        return null; // not supported
    }


}
