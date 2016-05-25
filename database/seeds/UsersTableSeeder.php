<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'admin',
            'password' => bcrypt('admin')
        ]);
        
        DB::table('users')->insert([
            'username' => 'user',
            'password' => bcrypt('user')
        ]);     
    }
}
