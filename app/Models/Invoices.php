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

  public function scopeCountAll($query)
  {
    $this->query = $query;    
    $this->type = 'count';   

    return $this->queryRaw();
  }

  public static function FindStringOnField($sLimit, $sWhere, $sOrder)
  {
    $where = '';

    if ($sWhere != '')
      $where = "
        WHERE 
          pat.surname LIKE '%". $sWhere ."%' OR pat.name LIKE '%". $sWhere ."%' 
          OR inv.number LIKE '%". $sWhere ."%' OR inv.serial LIKE '%". $sWhere ."%'
          OR inv.type LIKE '%". $sWhere ."%' OR inv.exp_date LIKE '%". $sWhere ."%'
      ";

    if ($sOrder != '') {
      $order = "ORDER BY " . $sOrder;
    } else {
      $order = 'ORDER BY inv.surname ASC';
    }

    $query = "
      SELECT 
        inv.number, inv.serial, inv.type, inv.exp_date, 
        pat.idpat, CONCAT(pat.surname, ', ', pat.name) AS surname_name 
      FROM 
        invoices inv  
      INNER JOIN 
        patients pat ON inv.idpat=pat.idpat
      " . $where . " 
      " . $order . " 
      " . $sLimit . "
    ;";

    return DB::select($query);
  }

  public static function CountFindStringOnField($sWhere = '')
  {
    $where = '';

    if ($sWhere != '')
      $where = "
        WHERE 
          pat.surname LIKE '%". $sWhere ."%' OR pat.name LIKE '%". $sWhere ."%' 
          OR inv.number LIKE '%". $sWhere ."%' OR inv.serial LIKE '%". $sWhere ."%'
          OR inv.type LIKE '%". $sWhere ."%' OR inv.exp_date LIKE '%". $sWhere ."%'
      ";

    $query = "
      SELECT 
        count(*) AS total
      FROM (
        SELECT 
          inv.number, inv.serial, inv.type, inv.exp_date, 
          pat.idpat, CONCAT(pat.surname, ', ', pat.name) AS surname_name 
        FROM 
          invoices inv  
        INNER JOIN 
          patients pat ON inv.idpat=pat.idpat
        " . $where . " 
      ) AS table1
    ;";

    return DB::select($query);
  }

}
