<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personal extends Model
{
	use SoftDeletes;
	
	protected $table = 'personal';
	protected $dates = ['deleted_at'];
    protected $fillable = ['nom','ape','cargo','dni','tel1','tel2','notas','direc','pobla','fenac'];
    protected $primaryKey = 'idper';
}