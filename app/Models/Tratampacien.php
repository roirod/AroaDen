<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratampacien extends Model
{
	protected $table = 'tratampacien';
    protected $fillable = ['idpac','idser','price','units','paid','date','tax','per1','per2'];
    protected $primaryKey = 'idtra';

    public function pacientes()
    {
        return $this->belongsTo('App\Models\Pacientes', 'idpac', 'idpac');
    }

    public function servicios()
    {
        return $this->belongsTo('App\Models\Servicios', 'idser', 'idser');
    }

}