<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class StaffSeeder extends Seeder
{
    public function run()
    {
    	$faker = Faker::create('es_ES');

    	foreach (range(1,50) as $index) {
	        DB::table('staff')->insert([
	            'surname' => htmlentities ($faker->lastName, ENT_QUOTES, "UTF-8").' '.htmlentities ($faker->lastName, ENT_QUOTES, "UTF-8"),
	            'name' => htmlentities ($faker->firstName, ENT_QUOTES, "UTF-8"),
              'dni' => $faker->numberBetween($min = 100000000, $max = 999999999),
	            'birth' => $faker->date,
	            'address' => $faker->address,
	            'city' => $faker->city
	        ]);
        }
    }
}
