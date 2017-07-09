<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiciosTable extends Migration
{
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->increments('idser');
            $table->string('name',111);
            $table->smallInteger('price');
            $table->tinyInteger('tax')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->unique('name');       
        });
    }
    
    public function down()
    {
        Schema::drop('servicios');
    }
}
