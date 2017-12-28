<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffWorks extends Model
{
	protected $table = 'staff_works';
    protected $fillable = ['idsta','idtre'];
    protected $primaryKey = 'idstwo';

    public function scopeAllById($query, $id)
    {
        return $query->select('idsta','idtre')
                    ->where('idtre', $id)
                    ->get();
    }

}