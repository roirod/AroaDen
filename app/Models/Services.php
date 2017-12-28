<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Services extends Model
{
	use SoftDeletes;

	protected $table = 'services';
	protected $dates = ['deleted_at'];
    protected $fillable = ['name','price','tax'];
    protected $primaryKey = 'idser';

    public function treatments()
    {
        return $this->hasMany('App\Models\Treatments', 'idser', 'idser');
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
        $exists = DB::table('services')
                        ->where('name', $name)
                        ->where('idser', '!=', $id)
                        ->first();

        if ( is_null($exists) ) {
            return true;
        }

        return $exists;
    }

    public function scopeFindStringOnField($query, $string)
    {
        return $query->whereNull('deleted_at')
                        ->where('name', 'LIKE', '%'.$string.'%')
                        ->orderBy('name','ASC')
                        ->get();
    }

    public static function CountFindStringOnField($string)
    {
        $result = DB::table('services')
                    ->whereNull('deleted_at')
                    ->where('name', 'LIKE', '%'.$string.'%')
                    ->get();

        return count($result);
    }

}