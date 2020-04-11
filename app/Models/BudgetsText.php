<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use App\Models\BaseModel;

class BudgetsText extends BaseModel
{
  use GetTableNameTrait;

  protected $table = 'budgets_text';
  protected $fillable = ['uniqid','text'];
  protected $primaryKey = 'uniqid';
  public $timestamps = false;

  public function budgets()
  {
    return $this->belongsTo('App\Models\Budgets');
  }

}
