<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use App\Models\BaseModel;
use Exception;
use Lang;

class Invoices extends BaseModel
{
	protected $table = 'invoices';
  protected $fillable = ['number','serial','idpat','type','parent_num','parent_serial','exp_date','place','notes'];
  protected $primaryKey = 'number';
  public $incrementing = false;  
  public $timestamps = false;

  public function patients()
  {
    return $this->belongsTo('App\Models\Patients');
  }   

  public function invoiceLines()
  {
    return $this->hasMany('App\Models\InvoiceLines', 'number', 'number');
  }

  public function scopeFirstById($query, $id)
  {
    $this->query = $query;    
    $this->whereRaw = "$this->primaryKey = '$id'";

    return $this->queryRaw();
  }

  public function scopeAllByPatient($query, $id)
  {
    $this->query = $query;    
    $this->whereRaw = "idpat = '$id'";
    $this->type = 'get';   

    return $this->queryRaw();
  }

  public function scopeGetNextNumber($query)
  {
    $this->query = $query;
    $this->selectRaw = "MAX($this->primaryKey) AS 'MaxValue'";

    $res = $this->queryRaw();

    if ($res->MaxValue == NULL)
      return 1;

    return $res->MaxValue + 1;
  }

}
