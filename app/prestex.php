<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class prestex extends Model
{

	protected $table = 'prestex';
    protected $fillable = ['idpac','cod','texto'];
    protected $primaryKey = 'idprestex';

}
