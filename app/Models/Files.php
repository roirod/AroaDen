<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;

class Files extends Model
{
  use GetTableNameTrait;

  protected $table = 'files';
  protected $fillable = ['iduser', 'type', 'info', 'originalName'];
  protected $primaryKey = 'idfiles';
  public $timestamps = false;

  public static function GetFilesByUserId($iduser, $type)
  {
      $files = DB::table('files')
                  ->where('iduser', $iduser)
                  ->where('type', $type)                  
                  ->orderBy('originalName', 'ASC')
                  ->get();

      return $files;
  }

  public static function CheckIfFileExist($iduser, $type, $originalName)
  {
      $file = DB::table('files')
                  ->where('iduser', $iduser)
                  ->where('type', $type)                                   
                  ->where('originalName', $originalName)
                  ->first();

      if ($file !== NULL) {
          if ($file->originalName == $originalName)
              return true;
      }

      return false;
  }

  public static function GetFileByUserId($iduser, $idfiles)
  {
      $file = DB::table('files')
                  ->where('iduser', $iduser)
                  ->where('idfiles', $idfiles)
                  ->first();

      return $file;
  }
}