<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class servicesSeeder extends Seeder
{
    public function run()
    {
    	$array = [
    		[
    			'name' => 'Empaste',
    			'price' => '44',
    		],
    		[
    			'name' => 'Endodoncia',
    			'price' => '333',
    		],
    		[
    			'name' => 'Endodoncia compleja',
    			'price' => '444',
    		],
    		[
    			'name' => 'Ortodoncia',
    			'price' => '222',
    		],
    		[
    			'name' => 'Ortodoncia compleja',
    			'price' => '44',
    		],
    		[
    			'name' => 'Limpieza',
    			'price' => '55',
    		]
    	];

    	$tax = [0, 4, 10, 21];

    	foreach ($array as $arr) {

            $k = array_rand($tax);
            $v = $tax[$k];

	        DB::table('services')->insert([
	            'name' => htmlentities ($arr['name'], ENT_QUOTES, "UTF-8"),
	            'price' => htmlentities ($arr['price'], ENT_QUOTES, "UTF-8"),
	            'tax' => $v,
	        ]);
        }
    }
}
