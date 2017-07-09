<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
	protected $table = 'ficha';
    protected $fillable = ['idpac','histo','enfer','medic','aler','notes'];
    protected $primaryKey = 'idpac';

    public function pacientes()
    {
        return $this->belongsTo('App\Models\Pacientes');
    }    
}
