<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empre extends Model
{
	protected $table = 'empre';
    protected $fillable = ['nom','direc','pobla','nif','tel1','tel2','tel3','notas','factumun','factutex','presutex'];
    protected $primaryKey = 'id';
}
