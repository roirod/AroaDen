<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLines extends Model
{
  protected $table = 'invoice_lines';
  protected $fillable = ['number','idtre'];
  protected $primaryKey = 'idinli';
}