<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class empre extends Model
{
	protected $table = 'empre';
    protected $fillable = ['nom','direc','pobla','nif','tel1','tel2','tel3','notas','presutex'];
    protected $primaryKey = 'id';
}
