<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Appointments extends Model
{
    protected $table = 'citas';
    protected $fillable = ['idpac','day','hour','notes'];
    protected $primaryKey = 'idcit';

    public function patients()
    {
        return $this->belongsTo('App\Models\Patients');
    }    

    public function scopeAllOrderByDay($query)
    {
        return $query->join('pacientes','citas.idpac','=','pacientes.idpac')
                        ->select('citas.*','pacientes.surname','pacientes.name')
                        ->whereNull('pacientes.deleted_at')
                        ->orderBy('citas.day' , 'DESC')
                        ->orderBy('citas.hour' , 'ASC')
                        ->get();
    }

    public static function CountAll()
    {
        $result = DB::table('citas')
            ->join('pacientes','citas.idpac','=','pacientes.idpac')
            ->select('citas.*','pacientes.surname','pacientes.name')
            ->whereNull('pacientes.deleted_at')
            ->count();

        $count = count($result);

        return (int)$count;
    }

    public function scopeAllBetweenRangeOrderByDay($query, $selfe1, $selfe2)
    {
        return $query->join('pacientes','citas.idpac','=','pacientes.idpac')
                        ->select('citas.*','pacientes.surname','pacientes.name')
                        ->whereBetween('day', [$selfe1, $selfe2])
                        ->whereNull('pacientes.deleted_at')
                        ->orderBy('day' , 'DESC')
                        ->orderBy('hour' , 'ASC')
                        ->get(); 
    }

    public function scopeAllTodayOrderByDay($query)
    {
        $today = date('Y-m-d');

        return $query->join('pacientes','citas.idpac','=','pacientes.idpac')
                        ->select('citas.*','pacientes.surname','pacientes.name')
                        ->where('citas.day', $today)
                        ->whereNull('pacientes.deleted_at')
                        ->orderBy('citas.day' , 'DESC')
                        ->orderBy('citas.hour' , 'ASC')
                        ->get();
    }

    public static function CountAllToday()
    {
        $today = date('Y-m-d');

        $result = DB::table('citas')
                        ->join('pacientes','citas.idpac','=','pacientes.idpac')
                        ->where('citas.day', $today)
                        ->whereNull('pacientes.deleted_at')
                        ->get();

        $count = count($result);

        return (int)$count;
    }

    public function scopeAllByPatientId($query, $id)
    {
        return $query->select('citas.*')
                    ->where('idpac', $id)
                    ->orderBy('day', 'DESC')
                    ->orderBy('hour', 'DESC')
                    ->get();
    }

    public function scopeFirstById($query, $id)
    {
        return DB::table('citas')
            ->join('pacientes','citas.idpac','=','pacientes.idpac')
            ->select('citas.*', 'pacientes.name', 'pacientes.surname', 'pacientes.idpac')
            ->where('idcit', $id)
            ->first();
    }

    public function scopeCheckIfIdExists($query, $id)
    {
        return $query->where('idcit', $id)
                        ->exists();
    }

}
