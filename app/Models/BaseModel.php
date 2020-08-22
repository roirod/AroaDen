<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
  protected $query;
  protected $var;
  protected $toSql = false;
  protected $selectRaw;
  protected $whereRaw;
  protected $havingRaw;
  protected $orderByRaw;
  protected $type; // get first find count

  public function scopeFirstWhereRaw($query)
  {
    $res = $query->whereRaw($this->whereRaw);
    //$sql = $res->toSql();

    return $res->first();
  }

  public function queryRaw()
  {
    if (isset($this->selectRaw))
      $this->query->selectRaw($this->selectRaw);

    if (isset($this->whereRaw))
      $this->query->whereRaw($this->whereRaw);

    if (isset($this->havingRaw))
      $this->query->havingRaw($this->havingRaw);

    if (isset($this->orderByRaw))
      $this->query->orderByRaw($this->orderByRaw);

    if ($this->toSql)
      return $this->query->toSql();

    if (empty($this->type))
      $this->type = 'first';

    $type = $this->type;

    if (isset($this->var))
      return $this->query->$type($this->var);

    return $this->query->$type();
  }

  public function scopeCountAll($query)
  {
    return (int)$query->count();
  }

}
