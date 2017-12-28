<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Budgets extends Model
{
	protected $table = 'budgets';
    protected $fillable = ['idpat','idser','price','units','uniqid','tax','created_at'];
    protected $primaryKey = 'idbud';

    public function patients()
    {
        return $this->belongsTo('App\Models\Patients');
    }

    public static function AllById($id)
    {
        return DB::table('budgets')
                    ->join('services','budgets.idser','=','services.idser')
                    ->select('budgets.*','services.name')
                    ->where('budgets.idpat', $id)
                    ->orderBy('created_at','DESC')
                    ->get(); 
    }

    public static function AllByCode($uniqid)
    {
        return DB::table('budgets')
                ->join('services', 'budgets.idser','=','services.idser')
                ->select('budgets.*','services.name')
                ->where('uniqid', $uniqid)
                ->orderBy('services.name' , 'ASC')
                ->get();
    }

    public static function AllByIdOrderByName($id, $uniqid)
    {
        return DB::table('budgets')
                    ->join('services', 'budgets.idser','=','services.idser')
                    ->select('budgets.*','services.name')
                    ->where('idpat', $id)
                    ->where('uniqid', $uniqid)
                    ->orderBy('services.name' , 'ASC')
                    ->get();  
    }

    public static function AllGroupByCode($id)
    {
        return DB::table('budgets')
                ->groupBy('uniqid')
                ->having('idpat','=', $id)
                ->orderBy('created_at' , 'DESC')
                ->get(); 
    }


    public static function GetTaxTotal($uniqid)
    {
        return DB::table('budgets')
                ->selectRaw('SUM((units*price*tax)/100) AS tot')
                ->where('uniqid', $uniqid)
                ->get();
    }

    public static function GetNoTaxTotal($uniqid)
    {
        return DB::table('budgets')
                ->selectRaw('SUM(units*price)-SUM((units*price*tax)/100) AS tot')
                ->where('uniqid', $uniqid)
                ->get();   
    }

    public static function GetTotal($uniqid)
    {
        return DB::table('budgets')
                ->selectRaw('SUM(units*price) AS tot')
                ->where('uniqid', $uniqid)
                ->get();   
    }

}