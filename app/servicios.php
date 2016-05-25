<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class servicios extends Model
{
	protected $table = 'servicios';
    protected $fillable = ['nomser','precio','iva'];
    protected $primaryKey = 'idser';
}