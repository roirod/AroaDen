<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personal extends Model
{
	use SoftDeletes;
	
	protected $table = 'personal';
	protected $dates = ['deleted_at'];
    protected $fillable = ['name','surname','position','dni','tel1','tel2','address','city','birth','notes'];
    protected $primaryKey = 'idper';
}