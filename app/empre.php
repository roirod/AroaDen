<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class empre extends Model
{
	protected $table = 'empre';
    protected $fillable = ['nom','direc','pobla','nif','tel1','tel2','tel3','notas','factumun','factutex','presutex'];
    protected $primaryKey = 'id';
}
