<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class servicios extends Model
{
	use SoftDeletes;

	protected $table = 'servicios';
	protected $dates = ['deleted_at'];
    protected $fillable = ['nomser','precio','iva'];
    protected $primaryKey = 'idser';
}