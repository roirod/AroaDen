<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use App\Models\BaseModel;

class BudgetsText extends BaseModel
{
  use GetTableNameTrait;

  protected $table = 'budgets_text';
  protected $fillable = ['idpat', 'uniqid','text'];
  protected $primaryKey = ['idpat', 'uniqid'];
  public $incrementing = false;
  public $timestamps = false;

  public function budgets()
  {
    return $this->belongsTo('App\Models\Budgets');
  }

}
