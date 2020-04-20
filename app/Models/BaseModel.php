<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{

  protected $whereRaw;  
  protected $selectRaw;
  protected $type;
  protected $query;

  public function scopeFirstWhereRaw($query)
  {
    $res = $query->whereRaw($this->whereRaw);
    //$sql = $res->toSql();

    return $res->first();
  }

  private function queryRaw($res)
  {

    return $query->count();
  }


  public function scopeCountAll($query)
  {
    return (int)$query->count();
  }

}
