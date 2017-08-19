<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Presup extends Model
{
	protected $table = 'presup';
    protected $fillable = ['idpac','idser','price','units','code','tax'];
    protected $primaryKey = 'idpre';

    public function pacientes()
    {
        return $this->belongsTo('App\Models\Pacientes');
    }

    public static function scopeAllById($id)
    {
        return DB::table('presup')
                    ->join('servicios','presup.idser','=','servicios.idser')
                    ->select('presup.*','servicios.name')
                    ->where('presup.idpac', $id)
                    ->orderBy('code','ASC')
                    ->get(); 
    }

    public static function AllByIdOrderByName($id, $code)
    {
        return DB::table('presup')
                    ->join('servicios', 'presup.idser','=','servicios.idser')
                    ->select('presup.*','servicios.name')
                    ->where('idpac', $id)
                    ->where('code', $code)
                    ->orderBy('servicios.name' , 'ASC')
                    ->get();  
    }

    public static function AllGroupByCode($id)
    {
        return DB::table('presup')
                ->groupBy('code')
                ->having('idpac', $id)
                ->orderBy('code' , 'DESC')
                ->get(); 
    }

}