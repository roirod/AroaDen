<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLines extends Model
{
  protected $table = 'invoice_lines';
  protected $fillable = ['number','idtre','idser','price','units','paid','day','tax'];
  protected $primaryKey = 'idinli';
}