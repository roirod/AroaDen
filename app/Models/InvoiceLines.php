<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use App\Models\BaseModel;
use Exception;
use Lang;

class InvoiceLines extends BaseModel
{
  protected $table = 'invoice_lines';
  protected $fillable = ['number','idtre','idser','price','units','paid','day','tax'];
  protected $primaryKey = 'idinli';
  public $timestamps = false;

  public function scopeGetByNumber($query, $number)
  {
    $this->query = $query->join('services','invoice_lines.idser','=','services.idser')
        ->select('invoice_lines.*','services.name');

    $this->whereRaw = "number = '$number'";
    $this->query->orderBy('invoice_lines.day', 'DESC');
    $this->type = 'get';   

    return $this->queryRaw();
  }

}