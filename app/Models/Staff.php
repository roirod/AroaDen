<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\BaseModelInterface;

class Staff extends Model implements BaseModelInterface
{
	use SoftDeletes;
	
	protected $table = 'staff';
	protected $dates = ['deleted_at'];
    protected $fillable = ['name','surname','dni','tel1','tel2','address','city','birth','notes'];
    protected $primaryKey = 'idsta';

    public function staffPositionsEntries()
    {
        return $this->hasMany('App\Models\StaffPositionsEntries', 'idsta', 'idsta');
    }

    public function scopeAllOrderBySurname($query, $num_paginate)
    {
        return $query->whereNull('deleted_at')
                        ->orderBy('surname', 'ASC')
                        ->orderBy('name', 'ASC')
                        ->paginate($num_paginate);
    }

    public function scopeAllOrderBySurnameNoPagination()
    {
        $query = "
            SELECT
                sta.idsta, sta.surname, sta.name,
                (
                    SELECT GROUP_CONCAT(stapo.name)
                    FROM staff_positions_entries entr
                    INNER JOIN staff_positions stapo
                    ON stapo.idstpo = entr.idstpo
                    WHERE entr.idsta = sta.idsta             
                ) AS positions
            FROM 
                staff sta
            WHERE 
                deleted_at IS NULL
            ORDER BY 
                sta.surname ASC, sta.name ASC
        ;";

        return DB::select($query);
    }

    public static function CountAll()
    {
        $result = DB::table('staff')
                    ->whereNull('deleted_at')
                    ->get();

        return (int)count($result);
    }

    public function scopeFirstById($query, $id)
    {
        return $query->where('idsta', $id)
                        ->whereNull('deleted_at')
                        ->first();
    }

    public function scopeFirstByDniDeleted($query, $dni)
    {
        return $query->where('dni', $dni)
                        ->first();
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
                ->whereNull('patients.deleted_at')
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

    public static function CheckIfExistsOnUpdate($id, $dni)
    {
        $exists = DB::table('staff')
                    ->where('idsta', '!=', $id)
                    ->where('dni', $dni)
                    ->first();

        if ( is_null($exists) ) {
            return true;
        }

        return $exists;
    }

    public static function FindStringOnField($sLimit, $sWhere, $sOrder)
    {
        $where = '';

        if ($sWhere != '')
          $where = "
            AND (
                sta.surname LIKE '%". $sWhere ."%' OR sta.name LIKE '%". $sWhere ."%' OR
                sta.dni LIKE '%". $sWhere ."%' OR sta.tel1 LIKE '%". $sWhere ."%'
            ) 
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
                    SELECT GROUP_CONCAT(stapo.name)
                    FROM staff_positions_entries entr
                    INNER JOIN staff_positions stapo
                    ON stapo.idstpo = entr.idstpo
                    WHERE entr.idsta = sta.idsta             
                ) AS positions
            FROM 
                staff sta
            WHERE 
                deleted_at IS NULL 
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
            AND (
                sta.surname LIKE '%". $sWhere ."%' OR sta.name LIKE '%". $sWhere ."%' OR
                sta.dni LIKE '%". $sWhere ."%' OR sta.tel1 LIKE '%". $sWhere ."%'
            ) 
          ";

        $query = "
            SELECT count(*) AS total
            FROM 
                staff sta
            WHERE 
                deleted_at IS NULL 
                " . $where . " 
        ;";

        return DB::select($query);
    }

}