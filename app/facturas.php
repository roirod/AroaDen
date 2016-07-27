<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class facturas extends Model
{
	protected $table = 'facturas';
    protected $fillable = ['idpac','idser','precio','canti','factumun','cod','iva'];
    protected $primaryKey = 'idfac';

    public function pacientes()
    {
        return $this->belongsTo('App\pacientes');
    }
}
