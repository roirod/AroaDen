<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratampacien extends Model
{
	protected $table = 'tratampacien';
    protected $fillable = ['idpac','idser','precio','canti','pagado','fecha','iva','per1','per2'];
    protected $primaryKey = 'idtra';

    public function pacientes()
    {
        return $this->belongsTo('App\Models\Pacientes');
    }
}