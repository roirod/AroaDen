<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
	protected $table = 'invoices';
  protected $fillable = ['serial','type','parent_num','exp_place','exp_date','irpf','no_tax_msg','notes'];
  protected $primaryKey = 'number';
}