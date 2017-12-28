<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
	protected $table = 'record';
    protected $fillable = ['idpac','medical_record','diseases','medicines','allergies','notes'];
    protected $primaryKey = 'idpac';

    public function patients()
    {
        return $this->belongsTo('App\Models\Patients');
    }    
}