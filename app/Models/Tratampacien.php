<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tratampacien extends Model
{
	protected $table = 'tratampacien';
    protected $fillable = ['idpac','idser','price','units','paid','day','tax','per1','per2'];
    protected $primaryKey = 'idtra';

    public function pacientes()
    {
        return $this->belongsTo('App\Models\Pacientes', 'idpac', 'idpac');
    }

    public function servicios()
    {
        return $this->belongsTo('App\Models\Servicios', 'idser', 'idser');
    }

    public static function FirstById($id)
    {
        return DB::table('tratampacien')
            ->join('servicios','tratampacien.idser','=','servicios.idser')
            ->select('tratampacien.*','servicios.name')
            ->where('idtra', $id)
            ->first();
    }

    public static function ServicesById($id)
    {
        return DB::table('tratampacien')
                    ->join('servicios','tratampacien.idser','=','servicios.idser')
                    ->select('tratampacien.*','servicios.name as servicios_name')
                    ->where('idpac', $id)
                    ->orderBy('day','DESC')
                    ->get();
    }

    public static function PaidServicesById($id)
    {
        return DB::table('tratampacien')
                    ->join('servicios','tratampacien.idser','=','servicios.idser')
                    ->select('tratampacien.*','servicios.name as servicios_name', DB::raw('tratampacien.units*tratampacien.price AS total'))
                    ->where('idpac', $id)
                    ->where('tratampacien.paid', 'total')




                                
                    ->orderBy('day','DESC')
                    ->get();
    }



}