<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLines extends Model
{
  protected $table = 'invoiceLines';
  protected $fillable = ['idtre','idser','price','units','tax'];
  protected $primaryKey = 'idinli';
}