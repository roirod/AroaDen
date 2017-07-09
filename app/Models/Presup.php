<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presup extends Model
{
	protected $table = 'presup';
    protected $fillable = ['idpac','idser','price','units','code','tax'];
    protected $primaryKey = 'idpre';

    public function pacientes()
    {
        return $this->belongsTo('App\Models\Pacientes');
    }    
}