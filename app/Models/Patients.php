<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use App\Models\BaseModel;

class Patients extends BaseModel
{
  use GetTableNameTrait;

  protected $table = 'patients';
  protected $fillable = ['surname','name','dni','tel1','tel2','tel3','sex','address','city','birth','notes'];
  protected $primaryKey = 'idpat';

  public function record()
  {
      return $this->hasOne('App\Models\Record', 'idpat', 'idpat');
  }

  public function treatments()
  {
      return $this->hasMany('App\Models\Treatments', 'idpat', 'idpat');
  }

  public function appointments()
  {
      return $this->hasMany('App\Models\Appointments', 'idpat', 'idpat');
  }

  public function invoices()
  {
      return $this->hasMany('App\Models\Invoices', 'idpat', 'idpat');
  }

  public function budgets()
  {
      return $this->hasMany('App\Models\Budgets', 'idpat', 'idpat');
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

  public function scopeAllOrderBySurname($query, $num_paginate)
  {
      return $query->select('idpat', 'surname', 'name', 'dni', 'tel1', 'city')
                      ->orderBy('surname', 'ASC')
                      ->orderBy('name', 'ASC')
                      ->paginate($num_paginate);
  }

  public static function FindStringOnField($sLimit, $sWhere, $sOrder)
  {
      $where = '';

      if ($sWhere != '')
        $where = "
          WHERE 
              surname LIKE '%". $sWhere ."%' 
              OR name LIKE '%". $sWhere ."%' OR dni LIKE '%". $sWhere ."%'
              OR tel1 LIKE '%". $sWhere ."%' OR city LIKE '%". $sWhere ."%'
        ";

      if ($sOrder != '') {
        $order = "ORDER BY " . $sOrder;
      } else {
        $order = 'ORDER BY surname ASC';
      }

      $query = "
          SELECT idpat, CONCAT(surname, ', ', name) AS surname_name, dni, tel1, city
          FROM patients
          " . $where . " 
          " . $order . " 
          " . $sLimit . "
      ;";

      return DB::select($query);
  }

  public static function CountFindStringOnField($sWhere = '')
  {
      $where = '';
              
      if ($sWhere != '')
        $where = "
          WHERE 
              surname LIKE '%". $sWhere ."%' 
              OR name LIKE '%". $sWhere ."%' OR dni LIKE '%". $sWhere ."%'
              OR tel1 LIKE '%". $sWhere ."%' OR city LIKE '%". $sWhere ."%'
        ";

      $query = "
          SELECT count(*) AS total
          FROM patients
          " . $where . "
      ;";

      return DB::select($query);
  }

  public static function GetPayments($sLimit, $sWhere, $sOrder)
  {
      $having = '';
              
      if ($sWhere != '')
        $having = "
          AND (
              surname_name LIKE '%". $sWhere ."%'
          ) 
        ";

      if ($sOrder != '') {
        $order = "ORDER BY " . $sOrder;
      } else {
        $order = 'ORDER BY rest DESC';
      }

      $query = "
          SELECT 
              pa.idpat, CONCAT(pa.surname, ', ', pa.name) AS surname_name,
              SUM(tre.units*tre.price) as total, 
              SUM(tre.paid) as paid, 
              SUM(tre.units*tre.price)-SUM(tre.paid) as rest 
          FROM 
              treatments tre
          INNER JOIN 
              patients pa ON tre.idpat=pa.idpat 
          GROUP BY 
              tre.idpat 
          HAVING 
              tre.idpat=tre.idpat AND rest > 0 
              " . $having . "
          " . $order . " 
          " . $sLimit . "
      ";

      return DB::select($query);
  }

  public static function countPayments()
  {
      $query = "
          SELECT count(*) AS total
          FROM (
              SELECT 
                  pa.idpat, SUM(tre.units*tre.price)-SUM(tre.paid) as rest 
              FROM 
                  treatments tre
              INNER JOIN 
                  patients pa ON tre.idpat=pa.idpat 
              GROUP BY 
                  tre.idpat 
              HAVING 
                  tre.idpat=tre.idpat  AND rest > 0
          ) AS table1
      ;";

      return DB::select($query);
  }

  public static function countFilteredPayments($sWhere = '')
  {
      $having = '';
              
      if ($sWhere != '')
        $having = "
          AND (
              surname_name LIKE '%". $sWhere ."%'
          ) 
        ";

      $query = "
          SELECT 
              count(*) AS total
          FROM (
              SELECT 
                  CONCAT(pa.surname, ', ', pa.name) AS surname_name,
                  SUM(tre.units*tre.price)-SUM(tre.paid) as rest 
              FROM 
                  treatments tre
              INNER JOIN 
                  patients pa ON tre.idpat=pa.idpat 
              GROUP BY 
                  tre.idpat 
              HAVING 
                  tre.idpat=tre.idpat AND rest > 0 
                  " . $having . "
          ) AS table1
      ;";

      return DB::select($query);
  }

}
