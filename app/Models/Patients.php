<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Patients extends Model
{
    use SoftDeletes;
    
	protected $table = 'pacientes';
    protected $dates = ['deleted_at'];
    protected $fillable = ['surname','name','dni','tel1','tel2','tel3','sex','address','city','birth','notes'];
    protected $primaryKey = 'idpac';

    public function record()
    {
        return $this->hasOne('App\Models\Record', 'idpac', 'idpac');
    }

    public function treatments()
    {
        return $this->hasMany('App\Models\Treatments', 'idpac', 'idpac');
    }

    public function appointments()
    {
        return $this->hasMany('App\Models\Appointments', 'idpac', 'idpac');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoices', 'idpac', 'idpac');
    }

    public function budgets()
    {
        return $this->hasMany('App\Models\Budgets', 'idpac', 'idpac');
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

    public function scopeFindStringOnField($query, $search_in, $string)
    {
        return $query->select('idpac', 'surname', 'name', 'dni', 'tel1', 'city')
                        ->whereNull('deleted_at')
                        ->where($search_in, 'LIKE', '%'.$string.'%')
                        ->orderBy('surname','ASC')
                        ->orderBy('name','ASC')
                        ->get();
    }

    public static function CountFindStringOnField($search_in, $string)
    {
        $result = DB::table('pacientes')
                    ->whereNull('deleted_at')
                    ->where($search_in, 'LIKE', '%'.$string.'%')
                    ->get();

        return count($result);
    }

    public static function GetTotalPayments($number, $all = false)
    {
        $query = "
            SELECT pac.surname, pac.name, pac.idpac, 
            SUM(tra.units*tra.price) as total, 
            SUM(tra.paid) as paid, 
            SUM(tra.units*tra.price)-SUM(tra.paid) as rest 
            FROM treatments tra
            INNER JOIN pacientes pac
            ON tra.idpac=pac.idpac 
            WHERE pac.deleted_at IS NULL 
            GROUP BY tra.idpac 
            HAVING tra.idpac=tra.idpac  
            ORDER BY rest DESC
        ";

        if (!$all) {
            $query .= " LIMIT $number";
        }

        return DB::select($query);
    }

}
