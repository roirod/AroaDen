<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class pacSeeder extends Seeder
{
    public function run()
    {
    	$faker = Faker::create('es_ES');

    	foreach (range(1,1500) as $index) {
	        DB::table('pacientes')->insert([
	            'apepac' => htmlentities ($faker->lastName, ENT_QUOTES, "UTF-8").' '.htmlentities ($faker->lastName, ENT_QUOTES, "UTF-8"),
	            'nompac' => htmlentities ($faker->firstName, ENT_QUOTES, "UTF-8"),
	            'dni' => $faker->numberBetween($min = 10000000, $max = 99999999),
	            'fenac' => $faker->date,
	            'direc' => $faker->address,
	            'pobla' => $faker->city
	        ]);
        }
    }
}
