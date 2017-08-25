<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
	protected $table = 'users';
    protected $fillable = ['username','password','type'];
    protected $hidden = ['password', 'remember_token'];
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

}
