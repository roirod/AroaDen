<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
	protected $table = 'invoices';
  protected $fillable = ['serial','idpat','type','parent_num','parent_serial','exp_date','no_tax','notes'];
  protected $primaryKey = 'number';
}
