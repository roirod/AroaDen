<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pacientes extends Model
{
	 protected $table = 'pacientes';
    protected $fillable = ['nompac','apepac','dni','tel1','tel2','tel3','sexo','notas','direc','pobla','fenac'];
    protected $primaryKey = 'idpac';
}
