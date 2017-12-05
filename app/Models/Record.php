<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
	protected $table = 'ficha';
    protected $fillable = ['idpac','histo','enfer','medic','aler','notes'];
    protected $primaryKey = 'idpac';

    public function patients()
    {
        return $this->belongsTo('App\Models\Patients');
    }    
}
