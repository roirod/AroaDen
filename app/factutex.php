<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class factutex extends Model
{
	protected $table = 'factutex';
    protected $fillable = ['idpac','factumun','cod','texto'];
    protected $primaryKey = 'idfactex';
}
