<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
	protected $table = 'invoices';
    protected $fillable = ['idpac','idser','price','units','invoice_number','code','tax'];
    protected $primaryKey = 'idfac';

    public function patients()
    {
        return $this->belongsTo('App\Models\Patients');
    }
}