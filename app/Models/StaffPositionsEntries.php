<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;

class StaffPositionsEntries extends Model
{
    use GetTableNameTrait;

	protected $table = 'staff_positions_entries';
    protected $fillable = ['idsta','idstpo'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

    public function staffPositions()
    {
        return $this->belongsTo('App\Models\StaffPositions');
    }

    public static function AllByStaffId($idsta)
    {
        $result = DB::table('staff_positions_entries')
                    ->select('staff_positions_entries.idstpo')
                    ->where('staff_positions_entries.idsta', $idsta)
                    ->orderBy('staff_positions_entries.idstpo','DESC')
                    ->get();

        return $result->toArray();
    }

    public static function AllByStaffIdWithName($id)
    {
        $result = DB::table('staff_positions_entries')
                    ->join('staff_positions','staff_positions.idstpo','=','staff_positions_entries.idstpo')
                    ->select('staff_positions.name')
                    ->where('staff_positions_entries.idsta', $id)
                    ->orderBy('staff_positions.name','ASC')
                    ->get();

        return $result->toArray();
    }

}
