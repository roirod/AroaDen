<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BudgetsText extends Model
{
	protected $table = 'budgets_text';
    protected $fillable = ['idpat','uniqid','text'];
    protected $primaryKey = 'idbute';

    public static function FirstById($id, $uniqid)
    {
        return DB::table('budgets_text')
				->where('idpat', $id)
				->where('uniqid', $uniqid)
				->first();
    }

    public static function FirstByUniqid($uniqid)
    {
        return DB::table('budgets_text')
                ->where('uniqid', $uniqid)
                ->first();
    }

}
