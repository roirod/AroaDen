<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->increments('uid');
            $table->string('username',20);
            $table->string('password',60);
            $table->string('type',11);
            $table->timestamps();
            $table->unique('username');
        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}