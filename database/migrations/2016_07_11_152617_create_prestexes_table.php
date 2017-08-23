<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestexesTable extends Migration
{
    public function up()
    {
        Schema::create('prestex', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->increments('idprestex');
            $table->Integer('idpac')->unsigned();
            $table->string('uniqid', 16);
            $table->text('text')->nullable();
            $table->timestamps();
            $table->unique('uniqid');
            $table->foreign('idpac')
                  ->references('idpac')->on('pacientes')
                  ->onDelete('cascade');         
        });
    }

    public function down()
    {
        Schema::drop('prestex');
    }
}
