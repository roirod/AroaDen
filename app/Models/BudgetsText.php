<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use App\Models\BaseModel;

class BudgetsText extends BaseModel
{
  use GetTableNameTrait;

  protected $table = 'budgets_text';
  protected $fillable = ['idpat','uniqid','text'];
  protected $primaryKey = 'idbute';

  public function scopeFirstById($query, $id, $uniqid)
  {
    $this->whereRaw = "idpat = '$id' AND uniqid = '$uniqid'";

    return $this->scopeFirstWhereRaw($query);
  }

  public function scopeFirstByUniqid($query, $uniqid)
  {
    $this->whereRaw = "uniqid = '$uniqid'";

    return $this->scopeFirstWhereRaw($query);
  }

}
