<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use App\Models\Files;
use Exception;
use Storage;
use Image;
use Lang;
use DB;

trait DirTrait {

  public function getFirstJpgOnDir($dir)
  {
    $jpg = glob($dir . "/*.jpg");

    if ( isset($jpg[0]) )
        return $jpg[0];

    return false;        
  }

  public function deleteAllFilesOnDir($dir)
  {
    $files = glob($dir.'/*');

    foreach($files as $file) {
      if(is_file($file))
        unlink($file);
    }
  }

  public static function deleteDir($dir) 
  {
    if (is_dir($dir)) {
      $objects = scandir($dir);

      foreach ($objects as $object) {
        if ($object != "." && $object != "..") {
          if (filetype($dir."/".$object) == "dir")
            self::deleteDir($dir."/".$object);
          else 
            unlink($dir."/".$object);
        }
      }
      
      reset($objects);
      rmdir($dir);
    }
  }

}