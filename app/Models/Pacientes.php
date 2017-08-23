<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Pacientes extends Model
{
    use SoftDeletes;
    
	protected $table = 'pacientes';
    protected $dates = ['deleted_at'];
    protected $fillable = ['surname','name','dni','tel1','tel2','tel3','sex','address','city','birth','notes'];
    protected $primaryKey = 'idpac';

    public function ficha()
    {
        return $this->hasOne('App\Models\Ficha', 'idpac', 'idpac');
    }

    public function tratampacien()
    {
        return $this->hasMany('App\Models\Tratampacien', 'idpac', 'idpac');
    }

    public function citas()
    {
        return $this->hasMany('App\Models\Citas', 'idpac', 'idpac');
    }

    public function facturas()
    {
        return $this->hasMany('App\Models\Facturas', 'idpac', 'idpac');
    }

    public function presup()
    {
        return $this->hasMany('App\Models\Presup', 'idpac', 'idpac');
    }

    public function scopeAllOrderBySurname($query, $num_paginate)
    {
        return $query->select('idpac', 'surname', 'name', 'dni', 'tel1', 'city')
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
        return $query->where('idpac', $id)
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
        return DB::table('pacientes')
                    ->join('citas','pacientes.idpac','=','citas.idpac')
                    ->select('pacientes.*','citas.*')
                    ->where('pacientes.idpac', $id)
                    ->orderBy('day', 'DESC')
                    ->orderBy('hour', 'DESC')
                    ->get();
    }

    public function scopeServicesSumById($query, $id)
    {
        return DB::table('tratampacien')
                    ->selectRaw('SUM(units*price) AS total_sum, SUM(paid) AS total_paid, SUM(units*price)-SUM(paid) AS rest')
                    ->where('idpac', $id)
                    ->get();
    }

    public static function CheckIfExistsOnUpdate($id, $dni)
    {
        $exists = DB::table('pacientes')
                        ->where('idpac', '!=', $id)
                        ->where('dni', $dni)
                        ->first();

        if ( is_null($exists) ) {
            return true;
        }

        return $exists;
    }

    public function scopeFindStringOnField($query, $busen, $busca)
    {
        return $query->select('idpac', 'surname', 'name', 'dni', 'tel1', 'city')
                        ->whereNull('deleted_at')
                        ->where($busen, 'LIKE', '%'.$busca.'%')
                        ->orderBy('surname','ASC')
                        ->orderBy('name','ASC')
                        ->get();
    }

    public static function CountFindStringOnField($busen, $busca)
    {
        $result = DB::table('pacientes')
                    ->where($busen, 'LIKE', '%'.$busca.'%')
                    ->get();

        return count($result);
    }

    public static function GetTotalPayments($num_mostrado)
    {
        return DB::select("
            SELECT pac.surname, pac.name, pac.idpac, 
            SUM(tra.units*tra.price) as total, 
            SUM(tra.paid) as paid, 
            SUM(tra.units*tra.price)-SUM(tra.paid) as rest 
            FROM tratampacien tra
            INNER JOIN pacientes pac
            ON tra.idpac=pac.idpac 
            WHERE pac.deleted_at IS NULL 
            GROUP BY tra.idpac 
            HAVING tra.idpac=tra.idpac  
            ORDER BY rest DESC
            LIMIT $num_mostrado
        ");
    }

}
