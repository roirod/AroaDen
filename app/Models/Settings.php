<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Settings extends Model
{
	protected $table = 'settings';
    protected $fillable = ['key','value'];
    protected $primaryKey = 'id';

    public static function getValueByKey($field)
    {
        return DB::table('settings')
                ->where('key', $field)
                ->first();
    }

    public static function getArray()
    {
        $settings = DB::table('settings')
                    ->select('key', 'value')
                    ->get();

        $array = [];

        foreach ($settings as $value) {
            $array_key = $value->key;
            $array_val = $value->value;

            $array[$array_key] = $array_val;
        }

        return $array;
    }    

}