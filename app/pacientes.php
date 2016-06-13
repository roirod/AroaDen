<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class pacientes extends Model
{
    use SoftDeletes;
    
	protected $table = 'pacientes';
    protected $dates = ['deleted_at'];
    protected $fillable = ['nompac','apepac','dni','tel1','tel2','tel3','sexo','notas','direc','pobla','fenac'];
    protected $primaryKey = 'idpac';

    public function ficha()
    {
        return $this->hasOne('App\ficha', 'idpac', 'idpac');
    }

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
