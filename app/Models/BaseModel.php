<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{

  protected $whereRaw;  

  public function scopeFirstWhereRaw($query)
  {
    return $query->whereRaw($this->whereRaw)
                      ->first();
  }

  public function scopeCountAll($query)
  {
    return (int)$query->count();
  }

}
