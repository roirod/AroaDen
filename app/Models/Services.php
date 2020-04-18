<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use App\Models\BaseModel;
use Exception;
use Lang;

class Services extends BaseModel
{
  use GetTableNameTrait;

  protected $table = 'services';
  protected $fillable = ['name','price','tax'];
  protected $primaryKey = 'idser';

  public function treatments()
  {
    return $this->hasMany('App\Models\Treatments', 'idser', 'idser');
  }

  public function scopeAllOrderByName($query)
  {
    return $query->orderBy('name', 'ASC')
                      ->get();
  }

  public function scopeFirstById($query, $id)
  {
    $this->whereRaw = "$this->primaryKey = '$id'";

    return $this->scopeFirstWhereRaw($query);
  }

  public function scopeFirstByName($query, $name)
  {
    $this->whereRaw = "name = '$name'";

    return $this->scopeFirstWhereRaw($query);
  }

  public function scopeCheckIfExistsOnUpdate($query, $id, $name)
  {
    $this->whereRaw = "$this->primaryKey != '$id' AND name = '$name'";

    return $this->scopeFirstWhereRaw($query);
  }

  public static function checkDestroy($idser)
  {
    $result = DB::table('treatments')
        ->select('treatments.idtre')
        ->where('idser', $idser)
        ->first();

    if ($result !== NULL)
      throw new Exception(Lang::get('aroaden.service_delete_warning'));
  }

  public function scopeFindStringOnField($query, $string)
  {
    return $query->where('name', 'LIKE', '%'.$string.'%')
                      ->orderBy('name','ASC')
                      ->get();
  }

  public static function CountFindStringOnField($string)
  {
    $result = DB::table('services')
                  ->where('name', 'LIKE', '%'.$string.'%')
                  ->count();

    return $result;
  }

}