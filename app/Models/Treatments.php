<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\GetTableNameTrait;
use App\Models\StaffWorks;

class Treatments extends Model
{
  use GetTableNameTrait;

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

    $data['staff_works'] = StaffWorks::join('staff','staff_works.idsta','=','staff.idsta')
                    ->select('staff_works.*','staff.surname','staff.name')
                    ->whereIn('staff_works.idtre', $idtre_array)
                    ->orderBy('staff_works.idtre' , 'ASC')
                    ->get();

    return $data;
  }

  public static function SumByPatientId($id)
  {
    return DB::table('treatments')
        ->selectRaw('
          SUM( units * ( ROUND( (((price * tax) / 100) + price), 2) ) ) AS total_sum, SUM(paid) AS total_paid, 
          SUM( units * ( ROUND( (((price * tax) / 100) + price), 2) ) ) - SUM(paid) AS rest
        ')
        ->where('idpat', $id)
        ->get();
  }

  public static function PaidByPatientId($id)
  {
    $collection = DB::table('treatments')
      ->join('services','treatments.idser','=','services.idser')
      ->select('treatments.*','services.name as service_name', 
        DB::raw('treatments.units * ( ROUND( ((treatments.price * treatments.tax) / 100) + treatments.price , 2) ) AS total')
      )
      ->where('idpat', $id)
      ->orderBy('day','DESC')
      ->get();

    $array_data = [];

    foreach ($collection as $collect) {
      if ($collect->paid === $collect->total)
        $array_data[] = $collect;
    }

    return $array_data;
  }

  public static function getUpdatedPaidByPatientId($id)
  {
    $collection = DB::table('treatments')
                ->select('treatments.*', DB::raw('units * ( ROUND( (((price * tax) / 100) + price), 2) ) AS total'))
                ->where('idpat', $id)
                ->get();

    $array_data = [];

    foreach ($collection as $collect) {
      if ($collect->paid === $collect->total) {
        if ($collect->updated_at != NULL) {

          $updated_at = $collect->updated_at;

        } else {

          $updated_at = 'false';

        }

        $array_data[] = $updated_at;
      }
    }

    return $array_data;
  }

}