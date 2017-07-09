<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
	protected $table = 'settings';
    protected $fillable = ['key','value'];
    protected $primaryKey = 'id';

    protected function getValueByKey($key)
    {
        return $query->where('key', $key);
    }
}