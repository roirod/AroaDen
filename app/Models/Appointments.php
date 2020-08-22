<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use App\Models\BaseModel;

class Appointments extends BaseModel
{
  use GetTableNameTrait;

  protected $table = 'appointments';
  protected $tableAlias = 'appo';
  protected $fillable = ['idpat','day','hour','notes'];
  protected $primaryKey = 'idapp';

  public function patients()
  {
    return $this->belongsTo('App\Models\Patients');
  }    

  public function scopeAllOrderByDay($query)
  {
    return $query->join('patients','appointments.idpat','=','patients.idpat')
                    ->select('appointments.*','patients.surname','patients.name')
                    ->orderBy('appointments.day' , 'DESC')
                    ->orderBy('appointments.hour' , 'ASC')
                    ->get();
  }

  public static function CountAll()
  {
    $count = DB::table('appointments')
        ->join('patients','appointments.idpat','=','patients.idpat')
        ->select('appointments.*','patients.surname','patients.name')
        ->count();

    return (int)$count;
  }

  public function scopeAllBetweenRangeOrderByDay($query, $date1, $date2)
  {
    $result = $query->join('patients','appointments.idpat','=','patients.idpat')
                    ->select('appointments.*','patients.surname','patients.name')
                    ->whereBetween('day', [$date1, $date2])
                    ->orderBy('day' , 'DESC')
                    ->orderBy('hour' , 'ASC')
                    ->get();

    return $result;
  }

  public function scopeAllTodayOrderByDay($query)
  {
    $today = date('Y-m-d');

    return $query->join('patients','appointments.idpat','=','patients.idpat')
                    ->select('appointments.*','patients.surname','patients.name')
                    ->where('appointments.day', $today)
                    ->orderBy('appointments.day' , 'DESC')
                    ->orderBy('appointments.hour' , 'ASC')
                    ->get();
  }

  public static function CountAllToday()
  {
    $today = date('Y-m-d');

    $count = DB::table('appointments')
                    ->join('patients','appointments.idpat','=','patients.idpat')
                    ->where('appointments.day', $today)
                    ->count();

    return (int)$count;
  }

  public function scopeAllByPatientId($query, $id)
  {
      return $query->select('appointments.*')
                  ->where('idpat', $id)
                  ->orderBy('day', 'DESC')
                  ->orderBy('hour', 'DESC')
                  ->get();
  }

  public static function FirstById($id)
  {
    return DB::table('appointments')
        ->join('patients','appointments.idpat','=','patients.idpat')
        ->select('appointments.*', 'patients.name', 'patients.surname', 'patients.idpat')
        ->where('idapp', $id)
        ->first();
  }

  public function scopeCheckIfIdExists($query, $id)
  {
    return $query->where('idapp', $id)->exists();
  }

  public function scopeCountAll($query)
  {
    $this->query = $query;    
    $this->type = 'count';   

    return $this->queryRaw();
  }

  public function scopeFindStringOnField($query, $soffset, $sLimit, $sWhere, $sOrder)
  {
    $dates = explode(",", $sWhere);
    $order = "day asc, hour asc";

    if ($sOrder != '')
      $order = $sOrder.", hour asc";

    $this->query = $query;
    $this->selectRaw = "$this->table.idpat, CONCAT(pa.surname, ', ', pa.name) AS surname_name, day, hour, $this->table.notes";
    $this->query->join('patients as pa', DB::raw("$this->table.idpat"), '=', DB::raw('pa.idpat'));
    $this->whereRaw = "day BETWEEN CAST('". $dates[0] ."' AS DATE) AND CAST('". $dates[1] ."' AS DATE)";
    $this->orderByRaw = $order;

    if ($sLimit != '') {
      $this->query->offset($soffset);
      $this->query->limit($sLimit);
    }

    $this->type = 'get';   

    return $this->queryRaw();
  }

  public static function CountFindStringOnField($sWhere)
  {
    $dates = explode(",", $sWhere);

    $where = "
      WHERE day BETWEEN CAST('". $dates[0] ."' AS DATE) AND CAST('". $dates[1] ."' AS DATE) 
    ";

    $query = "
      SELECT 
        count(*) AS total
      FROM (
        SELECT 
          appointments.idpat, day 
        FROM 
          appointments
        INNER JOIN 
          patients pa ON appointments.idpat=pa.idpat 
        " . $where . " 
      ) AS table1
    ;";

    return DB::select($query);
  }

}
