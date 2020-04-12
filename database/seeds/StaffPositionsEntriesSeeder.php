<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class StaffPositionsEntriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $faker = Faker::create('es_ES');

      $staff = DB::table('staff')->pluck('idsta')->toArray();
      $staff_positions = DB::table('staff_positions')->pluck('idstpo')->toArray();

      foreach (range(1, 300) as $index) {

        $staffkey = array_rand($staff);
        $idsta = $staff[$staffkey];

        $key = array_rand($staff_positions);
        $idstpo = $staff_positions[$key];

        $exists = DB::table('staff_positions_entries')
                          ->where('idsta', $idsta)
                          ->where('idstpo', $idstpo)
                          ->exists();

        if ($exists)
          continue;

        DB::table('staff_positions_entries')->insert([
          'idsta' => $idsta,
          'idstpo' => $idstpo
        ]);
      }
    }
}
