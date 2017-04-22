<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pacientes extends Model
{
    use SoftDeletes;
    
	protected $table = 'pacientes';
    protected $dates = ['deleted_at'];
    protected $fillable = ['nompac','apepac','dni','tel1','tel2','tel3','sexo','notas','direc','pobla','fenac'];
    protected $primaryKey = 'idpac';

    public function ficha()
    {
        return $this->hasOne('App\Models\Ficha', 'idpac', 'idpac');
    }

    public function tratampacien()
    {
        return $this->hasMany('App\Models\Tratampacien', 'idpac', 'idpac');
    }

    public function citas()
    {
        return $this->hasMany('App\Models\Citas', 'idpac', 'idpac');
    }

    public function facturas()
    {
        return $this->hasMany('App\Models\Facturas', 'idpac', 'idpac');
    }

    public function presup()
    {
        return $this->hasMany('App\Models\Presup', 'idpac', 'idpac');
    }
    
}
