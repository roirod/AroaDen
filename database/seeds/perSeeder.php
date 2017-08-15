<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class perSeeder extends Seeder
{
    public function run()
    {
    	$faker = Faker::create('es_ES');

    	$position = ['dentista', 'auxiliar', 'higienista'];

    	foreach (range(1,50) as $index) {

    		$k = array_rand($position);
			$v = $position[$k];

	        DB::table('personal')->insert([
	            'surname' => htmlentities ($faker->lastName, ENT_QUOTES, "UTF-8").' '.htmlentities ($faker->lastName, ENT_QUOTES, "UTF-8"),
	            'name' => htmlentities ($faker->firstName, ENT_QUOTES, "UTF-8"),
	            'dni' => $faker->numberBetween($min = 10000000, $max = 99999999),
	            'birth' => $faker->date,
	            'address' => $faker->address,
	            'position' => htmlentities($v, ENT_QUOTES, "UTF-8"),
	            'city' => $faker->city
	        ]);
        }
    }
}
