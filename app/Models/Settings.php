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

    public static function getObject()
    {
        $empre = DB::table('settings')
        			->select('key', 'value')
        			->get();

        $obj = new class {};

        foreach ($empre as $arr => $value) {
            $obj_key = $value->key;
            $obj_val = $value->value;

            $obj->$obj_key = $obj_val;
        }

        return $obj;
    }

}