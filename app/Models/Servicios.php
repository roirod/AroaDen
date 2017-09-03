<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Servicios extends Model
{
	use SoftDeletes;

	protected $table = 'servicios';
	protected $dates = ['deleted_at'];
    protected $fillable = ['name','price','tax'];
    protected $primaryKey = 'idser';

    public function tratampacien()
    {
        return $this->hasMany('App\Models\Tratampacien', 'idser', 'idser');
    }

    public function scopeAllOrderByName($query)
    {
        return $query->whereNull('deleted_at')
                        ->orderBy('name', 'ASC')
                        ->get();

    }

    public function scopeFirstById($query, $id)
    {
        return $query->where('idser', $id)
                        ->whereNull('deleted_at')
                        ->first();
    }

    public function scopeCountAll($query)
    {
        return $query->whereNull('deleted_at')
                    ->count();
    }

    public function scopeFirstByNameDeleted($query, $name)
    {
        return $query->where('name', $name)
                        ->first();
    }

    public static function CheckIfExistsOnUpdate($id, $name)
    {
        $exists = DB::table('servicios')
                        ->where('name', $name)
                        ->where('idser', '!=', $id)
                        ->first();

        if ( is_null($exists) ) {
            return true;
        }

        return $exists;
    }

    public function scopeFindStringOnField($query, $busca)
    {
        return $query->whereNull('deleted_at')
                        ->where('name', 'LIKE', '%'.$busca.'%')
                        ->orderBy('name','ASC')
                        ->get();
    }

    public static function CountFindStringOnField($busca)
    {
        $result = DB::table('servicios')
                    ->whereNull('deleted_at')
                    ->where('name', 'LIKE', '%'.$busca.'%')
                    ->get();

        return count($result);
    }

}