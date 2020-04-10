<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GetTableNameTrait;

class Record extends Model
{
  use GetTableNameTrait;

  protected $table = 'record';
  protected $fillable = ['idpat','medical_record','diseases','medicines','allergies','notes'];
  protected $primaryKey = 'idpat';

  public function patients()
  {
    return $this->belongsTo('App\Models\Patients');
  }

}