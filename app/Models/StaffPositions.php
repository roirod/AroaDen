<?php

namespace App\Models;

use App\Models\GetTableNameTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\BaseModelInterface;
use Exception;
use Lang;

class StaffPositions extends Model implements BaseModelInterface
{
    use GetTableNameTrait;

	protected $table = 'staff_positions';
    protected $fillable = ['name'];
    protected $primaryKey = 'idstpo';

    public function scopeAllOrderByName($query)
    {
        return $query->orderBy('name', 'ASC')
                        ->get();
    }

    public function scopeFirstById($query, $id)
    {
        return $query->where('idstpo', $id)
                        ->first();
    }

    public function scopeFirstByName($query, $name)
    {
        return $query->where('name', $name)
                        ->first();
    }

    public static function CheckIfExistsOnUpdate($id, $name)
    {
        $exists = DB::table('staff_positions')
                        ->where('name', $name)
                        ->where('idstpo', '!=', $id)
                        ->first();

        if ( is_null($exists) ) {
            return true;
        }

        return $exists;
    }

    public static function CountAll()
    {
        $result = DB::table('staff_positions')
                    ->get();

        return (int)count($result);
    }

    public static function checkDestroy($idstpo)
    {
        $result = DB::table('staff_positions_entries')
            ->select('staff_positions_entries.idsta')
            ->where('idstpo', $idstpo)
            ->first();

        if ($result !== NULL)
            throw new Exception(Lang::get('aroaden.staff_positions_delete_warning'));
    }

}
