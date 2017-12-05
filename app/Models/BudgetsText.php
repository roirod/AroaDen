<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BudgetsText extends Model
{
	protected $table = 'prestex';
    protected $fillable = ['idpac','uniqid','created_at','text'];
    protected $primaryKey = 'idprestex';

    public static function FirstById($id, $uniqid)
    {
        return DB::table('prestex')
				->where('idpac', $id)
				->where('uniqid', $uniqid)
				->first();
    }

    public static function FirstByUniqid($uniqid)
    {
        return DB::table('prestex')
                ->where('uniqid', $uniqid)
                ->first();
    }

}
