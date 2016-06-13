<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ficha extends Model
{
	protected $table = 'ficha';
    protected $fillable = ['idpac','histo','enfer','medic','aler','notas'];
    protected $primaryKey = 'idpac';

    public function pacientes()
    {
        return $this->belongsTo('App\pacientes');
    }    
}
