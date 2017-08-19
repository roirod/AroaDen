<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestex extends Model
{
	protected $table = 'prestex';
    protected $fillable = ['idpac','code','text'];
    protected $primaryKey = 'idprestex';

    public static function FirstById($id, $code)
    {
        return DB::table('prestex')
				->where('idpac', $id)
				->where('code', $code)
				->first();
    }

}
