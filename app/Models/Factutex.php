<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factutex extends Model
{
	protected $table = 'factutex';
    protected $fillable = ['idpac','factumun','cod','texto'];
    protected $primaryKey = 'idfactex';
}
