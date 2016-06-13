<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class personal extends Model
{
	use SoftDeletes;
	
	protected $table = 'personal';
	protected $dates = ['deleted_at'];
    protected $fillable = ['nom','ape','cargo','dni','tel1','tel2','notas','direc','pobla','fenac'];
    protected $primaryKey = 'idper';
}