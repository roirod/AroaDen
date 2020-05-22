<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->smallIncrements('idser');
            $table->string('name',111);
            $table->decimal('price', 11, 2);
            $table->tinyInteger('tax')->unsigned();
            $table->timestamps();
            $table->unique('name');       
        });
    }
    
    public function down()
    {
        Schema::drop('services');
    }
}
