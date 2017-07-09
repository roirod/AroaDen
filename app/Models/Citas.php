<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citas extends Model
{
    protected $table = 'citas';
    protected $fillable = ['idpac','day','hour','notes'];
    protected $primaryKey = 'idcit';

    public function pacientes()
    {
        return $this->belongsTo('App\Models\Pacientes');
    }    
}
