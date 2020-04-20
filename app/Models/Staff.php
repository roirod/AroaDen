<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use App\Models\BaseModel;
use Exception;
use Lang;

class Staff extends BaseModel
{
  use GetTableNameTrait;

  protected $table = 'staff';
  protected $fillable = ['name','surname','dni','tel1','tel2','address','city','birth','notes'];
  protected $primaryKey = 'idsta';

  public function staffPositionsEntries()
  {
    return $this->hasMany('App\Models\StaffPositionsEntries', 'idsta', 'idsta');
  }

  public function scopeFirstById($query, $id)
  {
    $this->whereRaw = "$this->primaryKey = '$id'";

    return $this->scopeFirstWhereRaw($query);
  }

  public function scopeFirstByDni($query, $dni)
  {
    $this->whereRaw = "dni = '$dni'";

    return $this->scopeFirstWhereRaw($query);
  }

  public function scopeCheckIfDniExistsOnUpdate($query, $id, $dni)
  {
    $this->whereRaw = "$this->primaryKey != '$id' AND dni = '$dni'";

    return $this->scopeFirstWhereRaw($query);
  }

  public static function checkDestroy($idsta)
  {
    $result = DB::table('staff_works')
        ->select('staff_works.idtre')
        ->where('idsta', $idsta)
        ->first();

    if ($result !== NULL)
      throw new Exception(Lang::get('aroaden.staff_delete_warning'));
  }

  public function scopeAllOrderBySurname($query, $num_paginate)
  {
    return $query->orderBy('surname', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->paginate($num_paginate);
  }

  public function scopeAllOrderBySurnameNoPagination()
  {
    $query = "
      SELECT
        sta.idsta, sta.surname, sta.name,
        (
          SELECT GROUP_CONCAT(stapo.name SEPARATOR ', ')
          FROM staff_positions_entries entr
          INNER JOIN staff_positions stapo
          ON stapo.idstpo = entr.idstpo
          WHERE entr.idsta = sta.idsta             
        ) AS positions
      FROM 
        staff sta
      ORDER BY 
        sta.surname ASC, sta.name ASC
    ;";
    
    return DB::select($query);
  }

  public function scopeAllAppointmentsById($query, $id)
  {
    return DB::table('patients')
                ->join('appointments','patients.idsta','=','appointments.idsta')
                ->select('patients.*','appointments.*')
                ->where('patients.idsta', $id)
                ->orderBy('day', 'DESC')
                ->orderBy('hour', 'DESC')
                ->get();
  }

  public function scopeServicesById($query, $id)
  {
      return DB::table('treatments')
              ->join('patients', 'treatments.idpat','=','patients.idpat')
              ->join('services', 'treatments.idser','=','services.idser')
              ->select('treatments.*','patients.surname','patients.name','services.name as service_name')
              ->orderBy('day' , 'DESC')
              ->get();    
  }

  public function scopeServicesSumById($query, $id)
  {
      return DB::table('treatments')
                  ->selectRaw('SUM(units*price) AS total_sum, SUM(paid) AS total_paid, SUM(units*price)-SUM(paid) AS rest')
                  ->where('idsta', $id)
                  ->get();
  }

  public static function FindStringOnField($sLimit, $sWhere, $sOrder)
  {
      $having = '';

      if ($sWhere != '')
        $having = "
          HAVING
              surname_name LIKE '%". $sWhere ."%' OR dni LIKE '%". $sWhere ."%' OR 
              tel1 LIKE '%". $sWhere ."%' OR positions LIKE '%". $sWhere ."%'
        ";

      if ($sOrder != '') {
        $order = "ORDER BY " . $sOrder;
      } else {
        $order = 'ORDER BY sta.surname ASC, sta.name ASC';
      }

      $query = "
          SELECT
              sta.idsta, CONCAT(sta.surname, ', ', sta.name) AS surname_name, sta.dni, sta.tel1,
              (
                  SELECT GROUP_CONCAT(stapo.name  SEPARATOR ', ')
                  FROM staff_positions_entries entr
                  INNER JOIN staff_positions stapo
                  ON stapo.idstpo = entr.idstpo
                  WHERE entr.idsta = sta.idsta             
              ) AS positions
          FROM 
              staff sta
          " . $having . " 
          " . $order . " 
          " . $sLimit . "
      ";

      return DB::select($query);
  }

  public static function CountFindStringOnField($sWhere = '')
  {
      $having = '';

      if ($sWhere != '')
        $having = "
          HAVING
              surname_name LIKE '%". $sWhere ."%' OR dni LIKE '%". $sWhere ."%' OR 
              tel1 LIKE '%". $sWhere ."%' OR positions LIKE '%". $sWhere ."%'
        ";

      $query = "
          SELECT 
              CONCAT(sta.surname, ', ', sta.name) AS surname_name, sta.dni, sta.tel1,
              (
                  SELECT GROUP_CONCAT(stapo.name  SEPARATOR ', ')
                  FROM staff_positions_entries entr
                  INNER JOIN staff_positions stapo
                  ON stapo.idstpo = entr.idstpo
                  WHERE entr.idsta = sta.idsta             
              ) AS positions
          FROM 
              staff sta
          " . $having . " 
      ";

      $query = "
          SELECT COUNT(*) AS total FROM ($query) AS table1;
      ";

      return DB::select($query);
  }

}