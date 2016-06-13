<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiciosTable extends Migration
{

    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->increments('idser');
            $table->string('nomser',111);
            $table->smallInteger('precio');
            $table->tinyInteger('iva')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->unique('nomser');       
        });
    }
    
    public function down()
    {
        Schema::drop('servicios');
    }
}
