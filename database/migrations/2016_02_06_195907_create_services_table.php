<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->increments('idser');
            $table->string('name',111);
            $table->smallInteger('price');
            $table->tinyInteger('tax')->default(0);
            $table->timestamps();
            $table->unique('name');       
        });
    }
    
    public function down()
    {
        Schema::drop('services');
    }
}
