<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use App\Models\BaseModel;
use Exception;
use Lang;

class StaffPositions extends BaseModel
{
  use GetTableNameTrait;

  protected $table = 'staff_positions';
  protected $fillable = ['name'];
  protected $primaryKey = 'idstpo';

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

  public static function checkDestroy($idstpo)
  {
      $result = DB::table('staff_positions_entries')
          ->select('staff_positions_entries.idsta')
          ->where('idstpo', $idstpo)
          ->first();

      if ($result !== NULL)
          throw new Exception(Lang::get('aroaden.staff_positions_delete_warning'));
  }

}
