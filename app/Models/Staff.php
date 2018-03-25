<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Staff extends Model
{
	use SoftDeletes;
	
	protected $table = 'staff';
	protected $dates = ['deleted_at'];
    protected $fillable = ['name','surname','position','dni','tel1','tel2','address','city','birth','notes'];
    protected $primaryKey = 'idsta';

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

    public function scopeFindStringOnField($query, $search_in, $string)
    {
        return $query->select('idsta', 'surname', 'name', 'dni', 'position', 'tel1')
                        ->whereNull('deleted_at')
                        ->where($search_in, 'LIKE', '%'.$string.'%')
                        ->orderBy('surname', 'ASC')
                        ->orderBy('name', 'ASC')
                        ->get();
    }

    public static function CountFindStringOnField($search_in, $string)
    {
        $result = DB::table('staff')
                        ->whereNull('deleted_at')
                        ->where($search_in, 'LIKE', '%'.$string.'%')
                        ->get();

        return count($result);
    }

}