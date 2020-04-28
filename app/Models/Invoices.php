<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
	protected $table = 'invoices';
  protected $fillable = ['serial','type','parent_num','exp_date','no_tax_msg','notes'];
  protected $primaryKey = 'number';
}