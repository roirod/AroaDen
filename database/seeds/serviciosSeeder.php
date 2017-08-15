<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class serviciosSeeder extends Seeder
{
    public function run()
    {
    	$array = [
    		[
    			'name' => 'empaste',
    			'price' => '44',
    		],
    		[
    			'name' => 'endodoncia',
    			'price' => '333',
    		],
    		[
    			'name' => 'endodoncia compleja',
    			'price' => '444',
    		],
    		[
    			'name' => 'ortodoncia',
    			'price' => '222',
    		],
    		[
    			'name' => 'ortodoncia compleja',
    			'price' => '44',
    		],
    		[
    			'name' => 'limpieza',
    			'price' => '55',
    		]
    	];

    	$tax = [0, 4, 10, 21];

    	foreach ($array as $arr) {

            $k = array_rand($tax);
            $v = $tax[$k];

	        DB::table('servicios')->insert([
	            'name' => htmlentities ($arr['name'], ENT_QUOTES, "UTF-8"),
	            'price' => htmlentities ($arr['price'], ENT_QUOTES, "UTF-8"),
	            'tax' => $v,
	        ]);
        }
    }
}
