<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

}