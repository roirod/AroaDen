<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facturas extends Model
{
	protected $table = 'facturas';
    protected $fillable = ['idpac','idser','price','units','invoice_number','code','tax'];
    protected $primaryKey = 'idfac';

    public function pacientes()
    {
        return $this->belongsTo('App\Models\Pacientes');
    }
}