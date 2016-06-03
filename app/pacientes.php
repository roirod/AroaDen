<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pacientes extends Model
{
	protected $table = 'pacientes';
    protected $fillable = ['nompac','apepac','dni','tel1','tel2','tel3','sexo','notas','direc','pobla','fenac'];
    protected $primaryKey = 'idpac';

    public function tratampacien()
    {
        return $this->hasMany('App\tratampacien', 'idpac', 'idpac');
    }

    public function citas()
    {
        return $this->hasMany('App\citas', 'idpac', 'idpac');
    }

    public function presup()
    {
        return $this->hasMany('App\presup', 'idpac', 'idpac');
    }
    
}
