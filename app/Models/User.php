<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;

class User extends Authenticatable
{
    use Notifiable,GetTableNameTrait;
    
	protected $table = 'users';
    protected $fillable = ['username', 'password', 'user_type', 'full_name'];
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

    public static function CheckIfExistsOnUpdate($id, $username)
    {
        $exists = DB::table('users')
                        ->where('uid', '!=', $id)
                        ->where('username', $username)
                        ->first();

        if ( isset($exists) )
            return true;

        return false;
    }

    public function getRememberToken()
    {
        return null; // not supported
    }

    public function setRememberToken($value)
    {}

    public function getRememberTokenName()
    {
        return null; // not supported
    }


}
