<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class AppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = Faker::create('es_ES');

      $patients = DB::table('patients')->pluck('idpat')->toArray();

      foreach (range(1, 500) as $index) {

        $key = array_rand($patients);
        $val = $patients[$key];

        DB::table('appointments')->insert([
          'idpat' => $val,
          'day' => htmlentities ($faker->dateTimeBetween($startDate = '-30 days', $endDate = '+90 days')->format("Y-m-d"), ENT_QUOTES, "UTF-8"),
          'hour' => htmlentities ($faker->dateTimeBetween()->format("H:i:s"), ENT_QUOTES, "UTF-8")
        ]);
      }
    }
}
