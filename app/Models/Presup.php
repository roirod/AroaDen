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

    public function scopeAllById($query, $id)
    {
        return DB::table('presup')
                    ->join('servicios','presup.idser','=','servicios.idser')
                    ->select('presup.*','servicios.name')
                    ->where('presup.idpac', $id)
                    ->orderBy('cod','ASC')
                    ->get(); 
    }

}