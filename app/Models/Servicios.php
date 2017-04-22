<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicios extends Model
{
	use SoftDeletes;

	protected $table = 'servicios';
	protected $dates = ['deleted_at'];
    protected $fillable = ['nomser','precio','iva'];
    protected $primaryKey = 'idser';
}