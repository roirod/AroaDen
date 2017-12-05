<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Treatments extends Model
{
	protected $table = 'treatments';
    protected $fillable = ['idpac','idser','price','units','paid','day','tax','per1','per2'];
    protected $primaryKey = 'idtra';

    public function patients()
    {
        return $this->belongsTo('App\Models\Patients', 'idpac', 'idpac');
    }

    public function services()
    {
        return $this->belongsTo('App\Models\Services', 'idser', 'idser');
    }

    public static function FirstById($id)
    {
        return DB::table('treatments')
            ->join('servicios','treatments.idser','=','servicios.idser')
            ->select('treatments.*','servicios.name')
            ->where('idtra', $id)
            ->first();
    }

    public static function ServicesById($id)
    {
        return DB::table('treatments')
                    ->join('servicios','treatments.idser','=','servicios.idser')
                    ->select('treatments.*','servicios.name as servicios_name')
                    ->where('idpac', $id)
                    ->orderBy('day','DESC')
                    ->get();
    }

    public static function PaidServicesById($id)
    {
        $collection = DB::table('treatments')
                    ->join('servicios','treatments.idser','=','servicios.idser')
                    ->select('treatments.*','servicios.name as servicios_name', DB::raw('treatments.units*treatments.price AS total'))
                    ->where('idpac', $id)
                    ->orderBy('day','DESC')
                    ->get();

        $array_data = [];

        foreach ($collection as $collect) {
            if ((int)$collect->paid === (int)$collect->total)
                $array_data[] = $collect;
        }

        return $array_data;
    }

}