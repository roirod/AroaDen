<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class personal extends Model
{
	protected $table = 'personal';
    protected $fillable = ['nom','ape','cargo','dni','tel1','tel2','notas','direc','pobla','fenac'];
    protected $primaryKey = 'idper';
}