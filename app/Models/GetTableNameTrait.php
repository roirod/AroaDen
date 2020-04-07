<?php

namespace App\Models;

trait GetTableNameTrait
{

  public static function getTableName()
  {
    return ((new self)->getTable());
  }

}
