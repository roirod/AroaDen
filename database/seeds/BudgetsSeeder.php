<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class BudgetsSeeder extends Seeder
{

  public function run()
  {
    $faker = Faker::create('es_ES');

    $num = $faker->numberBetween($min = 3, $max = 6);
    $num_budgets = 400;

    $patients = DB::table('patients')->pluck('idpat')->toArray();
    $services = DB::table('services')->pluck('idser')->toArray();

    foreach (range(1, $num_budgets) as $index) {

      $patkey = array_rand($patients);
      $idpat = $patients[$patkey];

      $uniqid = uniqid();
      $created_at = $faker->dateTimeBetween($startDate = '-30 days', $endDate = '+90 days');

      foreach (range(1, $num) as $index) {

        $serkey = array_rand($services);
        $idser = $services[$serkey];

        $servi = DB::table('services')->where('idser', $idser)->first();

        $units = $faker->numberBetween($min = 1, $max = 4);

        $exists = DB::table('budgets')
                          ->where('idpat', $idpat)
                          ->where('idser', $idser)
                          ->where('uniqid', $uniqid)                         
                          ->exists();

        if ($exists)
          continue;

        DB::table('budgets')->insert([
          'idpat' => $idpat,
          'idser' => $idser,
          'price' => $servi->price,
          'tax' => $servi->tax,        
          'units' => $units,
          'uniqid' => $uniqid,
          'created_at' => $created_at
        ]);

      }

    }

  }
}
