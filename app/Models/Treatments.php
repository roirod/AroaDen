<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Treatments extends Model
{
	protected $table = 'treatments';
    protected $fillable = ['idpat','idser','price','units','paid','day','tax'];
    protected $primaryKey = 'idtre';

    public function patients()
    {
        return $this->belongsTo('App\Models\Patients', 'idpat', 'idpat');
    }

    public function services()
    {
        return $this->belongsTo('App\Models\Services', 'idser', 'idser');
    }

    public static function FirstById($id)
    {
        return DB::table('treatments')
            ->join('services','treatments.idser','=','services.idser')
            ->select('treatments.*','services.name')
            ->where('idtre', $id)
            ->first();
    }

    public function scopeAllByPatientId($query, $id)
    {
        $data = [];

        $data['treatments'] = $query->join('services','treatments.idser','=','services.idser')
                    ->select('treatments.*','services.name as service_name')
                    ->where('idpat', $id)
                    ->orderBy('day','DESC')
                    ->get();

        $treatments = $data['treatments']->toArray();

        $idtre_array = array_column($treatments, 'idtre');

        $data['staff_works'] = DB::table('staff_works')
                    ->select('idsta','idtre')
                    ->whereIn('idtre', $idtre_array)
                    ->get();

        return $data;
    }

    public static function SumByPatientId($id)
    {
        return DB::table('treatments')
                    ->selectRaw('SUM(units*price) AS total_sum, SUM(paid) AS total_paid, SUM(units*price)-SUM(paid) AS rest')
                    ->where('idpat', $id)
                    ->get();
    }

    public static function PaidByPatientId($id)
    {
        $collection = DB::table('treatments')
                    ->join('services','treatments.idser','=','services.idser')
                    ->select('treatments.*','services.name as service_name', DB::raw('treatments.units*treatments.price AS total'))
                    ->where('idpat', $id)
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