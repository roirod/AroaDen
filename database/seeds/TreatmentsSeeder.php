<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class TreatmentsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker::create('es_ES');

    $num_treatments = 200;
    $num_staff_works = 300;

    $patients = DB::table('patients')->pluck('idpat')->toArray();
    $services = DB::table('services')->pluck('idser')->toArray();

    foreach (range(1, $num_treatments) as $index) {

      $patkey = array_rand($patients);
      $patval = $patients[$patkey];

      $serkey = array_rand($services);
      $serval = $services[$serkey];

      $servi = DB::table('services')->where('idser', $serval)->first();

      $units = $faker->numberBetween($min = 1, $max = 4);
      $paid = $faker->numberBetween($min = 1, $max = $servi->price * $units);

      DB::table('treatments')->insert([
        'idpat' => $patval,
        'idser' => $serval,
        'price' => $servi->price,
        'units' => $units,
        'paid' => $paid,
        'day' => htmlentities ($faker->dateTimeBetween($startDate = '-30 days', $endDate = '+90 days')->format("Y-m-d"), ENT_QUOTES, "UTF-8"),
        'tax' => $servi->tax
      ]);
    }
  

    $staff = DB::table('staff')->pluck('idsta')->toArray();
    $treatments = DB::table('treatments')->pluck('idtre')->toArray();

    foreach (range(1, $num_staff_works) as $index) {

      $stakey = array_rand($staff);
      $staval = $staff[$stakey];

      $treakey = array_rand($treatments);
      $treaval = $treatments[$treakey];

      DB::table('staff_works')->insert([
        'idsta' => $staval,
        'idtre' => $treaval
      ]);
    }

  }
}
