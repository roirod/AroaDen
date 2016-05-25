<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class citas extends Model
{
    protected $table = 'citas';
    protected $fillable = ['idpac','notas','diacit','horacit'];
    protected $primaryKey = 'idcit';
}
