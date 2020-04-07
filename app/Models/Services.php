<?php

namespace App\Models;

use App\Models\GetTableNameTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\BaseModelInterface;
use Exception;
use Lang;

class Services extends Model implements BaseModelInterface
{
    use GetTableNameTrait;

	protected $table = 'services';
    protected $fillable = ['name','price','tax'];
    protected $primaryKey = 'idser';

    public function treatments()
    {
        return $this->hasMany('App\Models\Treatments', 'idser', 'idser');
    }

    public function scopeAllOrderByName($query)
    {
        return $query->orderBy('name', 'ASC')
                        ->get();
    }

    public function scopeFirstById($query, $id)
    {
        return $query->where('idser', $id)
                        ->first();
    }

    public static function checkDestroy($idser)
    {
        $result = DB::table('treatments')
            ->select('treatments.idtre')
            ->where('idser', $idser)
            ->first();

        if ($result !== NULL)
            throw new Exception(Lang::get('aroaden.service_delete_warning'));
    }

    public static function CountAll()
    {
        $result = DB::table('services')
                    ->count();

        return (int)$result;
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
        return $query->where('name', 'LIKE', '%'.$string.'%')
                        ->orderBy('name','ASC')
                        ->get();
    }

    public static function CountFindStringOnField($string)
    {
        $result = DB::table('services')
                    ->where('name', 'LIKE', '%'.$string.'%')
                    ->count();

        return $result;
    }

}