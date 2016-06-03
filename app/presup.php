<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class presup extends Model
{
	protected $table = 'presup';
    protected $fillable = ['idpac','idser','precio','canti','cod','iva'];
    protected $primaryKey = 'idpre';

    public function pacientes()
    {
        return $this->belongsTo('App\pacientes');
    }
    
}