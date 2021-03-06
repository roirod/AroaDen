<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use StdClass;

class Settings extends Model
{
  use GetTableNameTrait;

  protected $table = 'settings';
  protected $fillable = ['key','value', 'type'];
  protected $primaryKey = ['key', 'type'];
  public $incrementing = false;
  public $timestamps = false;
  protected $keyType = 'string';

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

  public static function getObject()
  {
    $settings = DB::table('settings')
                ->select('key', 'value')
                ->get();

    $obj = new StdClass;

    foreach ($settings as $setting) {
      $key = $setting->key;
      $value = $setting->value;

      $obj->$key = $value;
    }

    return $obj;
  }

  public function scopeGetCompanyData($query)
  {
    return $query->select('key', 'value', 'type')
                ->where('type', 'company_data')
                ->get();
  }

}