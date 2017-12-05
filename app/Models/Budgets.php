<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Budgets extends Model
{
	protected $table = 'presup';
    protected $fillable = ['idpac','idser','price','units','uniqid','created_at','tax'];
    protected $primaryKey = 'idpre';

    public function patients()
    {
        return $this->belongsTo('App\Models\Patients');
    }

    public static function AllById($id)
    {
        return DB::table('presup')
                    ->join('servicios','presup.idser','=','servicios.idser')
                    ->select('presup.*','servicios.name')
                    ->where('presup.idpac', $id)
                    ->orderBy('created_at','DESC')
                    ->get(); 
    }

    public static function AllByCode($uniqid)
    {
        return DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.name')
                ->where('uniqid', $uniqid)
                ->orderBy('servicios.name' , 'ASC')
                ->get();
    }

    public static function AllByIdOrderByName($id, $uniqid)
    {
        return DB::table('presup')
                    ->join('servicios', 'presup.idser','=','servicios.idser')
                    ->select('presup.*','servicios.name')
                    ->where('idpac', $id)
                    ->where('uniqid', $uniqid)
                    ->orderBy('servicios.name' , 'ASC')
                    ->get();  
    }

    public static function AllGroupByCode($id)
    {
        return DB::table('presup')
                ->groupBy('uniqid')
                ->having('idpac','=', $id)
                ->orderBy('created_at' , 'DESC')
                ->get(); 
    }


    public static function GetTaxTotal($uniqid)
    {
        return DB::table('presup')
                ->selectRaw('SUM((units*price*tax)/100) AS tot')
                ->where('uniqid', $uniqid)
                ->get();
    }

    public static function GetNoTaxTotal($uniqid)
    {
        return DB::table('presup')
                ->selectRaw('SUM(units*price)-SUM((units*price*tax)/100) AS tot')
                ->where('uniqid', $uniqid)
                ->get();   
    }

    public static function GetTotal($uniqid)
    {
        return DB::table('presup')
                ->selectRaw('SUM(units*price) AS tot')
                ->where('uniqid', $uniqid)
                ->get();   
    }

}