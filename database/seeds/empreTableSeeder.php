<?php

use Illuminate\Database\Seeder;

class empreTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('empre')->insert([
            'nom' => 'none',
            'direc' => 'none',
            'pobla' => 'none',
            'nif' => 'none',
            'tel1' => 'none',
            'tel2' => 'none',
            'tel3' => 'none',
            'notas' => 'none',
            'presutex' => 'none'
        ]);
    }
}
