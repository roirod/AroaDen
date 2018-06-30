<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Files extends Model
{
	protected $table = 'files';
    protected $fillable = ['iduser','info', 'originalName'];
    protected $primaryKey = 'idfiles';

    public static function GetFilesByUserId($iduser)
    {
        $files = DB::table('files')
                    ->where('iduser', $iduser)
                    ->orderBy('originalName', 'ASC')
                    ->get();

        return $files;
    }

    public static function CheckIfFileExist($iduser, $originalName)
    {
        $file = DB::table('files')
                    ->where('iduser', $iduser)
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