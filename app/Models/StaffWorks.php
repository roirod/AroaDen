<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffWorks extends Model
{
	protected $table = 'staff_works';
    protected $fillable = ['idsta','idtre'];
    protected $primaryKey = 'idstwo';
    public $timestamps = false;
    
    public function scopeAllById($query, $id)
    {
        return $query->select('idsta','idtre')
                    ->where('idtre', $id)
                    ->get();
    }

    public function scopeAllByStaffId($query, $id)
    {
        return $query->join('treatments','treatments.idtre','=','staff_works.idtre')
        			->join('patients','patients.idpat','=','treatments.idpat')
        			->join('services','services.idser','=','treatments.idser')
                    ->select('treatments.*','patients.*','services.name as service_name')
                    ->where('staff_works.idsta', $id)
                    ->orderBy('treatments.day','DESC')
                    ->get();
    }
}