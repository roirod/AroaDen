<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Personal extends Model
{
	use SoftDeletes;
	
	protected $table = 'personal';
	protected $dates = ['deleted_at'];
    protected $fillable = ['name','surname','position','dni','tel1','tel2','address','city','birth','notes'];
    protected $primaryKey = 'idper';

    public function scopeAllOrderBySurname($query, $num_paginate)
    {
        return $query->whereNull('deleted_at')
                        ->orderBy('surname', 'ASC')
                        ->orderBy('name', 'ASC')
                        ->paginate($num_paginate);
    }

    public function scopeAllOrderBySurnameNoPagination($query)
    {
        return $query->whereNull('deleted_at')
                        ->orderBy('surname', 'ASC')
                        ->orderBy('name', 'ASC')
                        ->get();
    }

    public function scopeCountAll($query)
    {
        return $query->whereNull('deleted_at')
                    ->count();
    }

    public function scopeFirstById($query, $id)
    {
        return $query->where('idper', $id)
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
                    ->join('citas','pacientes.idper','=','citas.idper')
                    ->select('pacientes.*','citas.*')
                    ->where('pacientes.idper', $id)
                    ->orderBy('day', 'DESC')
                    ->orderBy('hour', 'DESC')
                    ->get();
    }

    public function scopeServicesById($query, $id)
    {
        return DB::table('tratampacien')
                ->join('pacientes', 'tratampacien.idpac','=','pacientes.idpac')
                ->join('servicios', 'tratampacien.idser','=','servicios.idser')
                ->select('tratampacien.*','pacientes.surname','pacientes.name','servicios.name as servicio_name')
                ->whereNull('pacientes.deleted_at')
                ->where('per1', $id)
                ->orWhere('per2', $id)
                ->orderBy('day' , 'DESC')
                ->get();    
    }

    public function scopeServicesSumById($query, $id)
    {
        return DB::table('tratampacien')
                    ->selectRaw('SUM(units*price) AS total_sum, SUM(paid) AS total_paid, SUM(units*price)-SUM(paid) AS rest')
                    ->where('idper', $id)
                    ->get();
    }

    public static function CheckIfExistsOnUpdate($id, $dni)
    {
        $exists = DB::table('personal')
                        ->where('idper', '!=', $id)
                        ->where('dni', $dni)
                        ->first();

        if ( is_null($exists) ) {
            return true;
        }

        return $exists;
    }

    public function scopeFindStringOnField($query, $busen, $busca)
    {
        return $query->select('idper', 'surname', 'name', 'dni', 'tel1', 'city')
                        ->whereNull('deleted_at')
                        ->where($busen, 'LIKE', '%'.$busca.'%')
                        ->orderBy('surname', 'ASC')
                        ->orderBy('name', 'ASC')
                        ->get();
    }

    public static function CountFindStringOnField($busen, $busca)
    {
        $result = DB::table('personal')
                        ->whereNull('deleted_at')
                        ->where($busen, 'LIKE', '%'.$busca.'%')
                        ->get();

        return count($result);
    }
}