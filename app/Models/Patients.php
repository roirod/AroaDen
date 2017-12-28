<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Patients extends Model
{
    use SoftDeletes;
    
	protected $table = 'patients';
    protected $dates = ['deleted_at'];
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

    public function scopeAllOrderBySurname($query, $num_paginate)
    {
        return $query->select('idpat', 'surname', 'name', 'dni', 'tel1', 'city')
                        ->whereNull('deleted_at')
                        ->orderBy('surname', 'ASC')
                        ->orderBy('name', 'ASC')
                        ->paginate($num_paginate);
    }

    public function scopeCountAll($query)
    {
        return $query->whereNull('deleted_at')
                    ->count();
    }

    public function scopeFirstById($query, $id)
    {
        return $query->where('idpat', $id)
                        ->whereNull('deleted_at')
                        ->first();
    }

    public function scopeFirstByDniDeleted($query, $dni)
    {
        return $query->where('dni', $dni)
                        ->first();
    }

    public function scopeAllCitasById($query, $id)
    {
        return DB::table('patients')
                    ->join('citas','patients.idpat','=','citas.idpat')
                    ->select('patients.*','citas.*')
                    ->where('patients.idpat', $id)
                    ->orderBy('day', 'DESC')
                    ->orderBy('hour', 'DESC')
                    ->get();
    }

    public function scopeServicesSumById($query, $id)
    {
        return DB::table('treatments')
                    ->selectRaw('SUM(units*price) AS total_sum, SUM(paid) AS total_paid, SUM(units*price)-SUM(paid) AS rest')
                    ->where('idpat', $id)
                    ->get();
    }

    public static function CheckIfExistsOnUpdate($id, $dni)
    {
        $exists = DB::table('patients')
                        ->where('idpat', '!=', $id)
                        ->where('dni', $dni)
                        ->first();

        if ( is_null($exists) ) {
            return true;
        }

        return $exists;
    }

    public function scopeFindStringOnField($query, $busen, $busca)
    {
        return $query->select('idpat', 'surname', 'name', 'dni', 'tel1', 'city')
                        ->whereNull('deleted_at')
                        ->where($busen, 'LIKE', '%'.$busca.'%')
                        ->orderBy('surname','ASC')
                        ->orderBy('name','ASC')
                        ->get();
    }

    public static function CountFindStringOnField($busen, $busca)
    {
        $result = DB::table('patients')
                    ->whereNull('deleted_at')
                    ->where($busen, 'LIKE', '%'.$busca.'%')
                    ->get();

        return count($result);
    }

    public static function GetTotalPayments($number, $all = false)
    {
        $query = "
            SELECT pat.surname, pat.name, pat.idpat, 
            SUM(tre.units*tre.price) as total, 
            SUM(tre.paid) as paid, 
            SUM(tre.units*tre.price)-SUM(tre.paid) as rest 
            FROM treatments tre
            INNER JOIN patients pat
            ON tre.idpat=pat.idpat 
            WHERE pat.deleted_at IS NULL 
            GROUP BY tre.idpat 
            HAVING tre.idpat=tre.idpat  
            ORDER BY rest DESC
        ";

        if (!$all) {
            $query .= " LIMIT $number";
        }

        return DB::select($query);
    }

}
