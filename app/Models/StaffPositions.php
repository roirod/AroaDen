<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\BaseModelInterface;

class StaffPositions extends Model implements BaseModelInterface
{
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

}
