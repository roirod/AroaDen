<?php

use Illuminate\Database\Seeder;

class StaffPositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$default_staff_positions = [
	        'Odontólogo',
	        'Auxiliar',
	        'Higienista',
	        'Endodoncista',
	        'Cirujano',
	        'Ortodoncista'
	    ];

	    foreach ($default_staff_positions as $position) {
	        DB::table('staff_positions')->insert([
	            'name' => htmlentities ($position, ENT_QUOTES, "UTF-8")
	        ]);
	    }
    }
}
