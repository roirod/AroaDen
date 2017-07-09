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
            $table->dateTime('code');
            $table->text('text')->nullable();
            $table->timestamps();
            $table->unique('code');  

            $table->foreign('idpac')
                  ->references('idpac')->on('pacientes')
                  ->onDelete('cascade');
            $table->foreign('code')
                  ->references('code')->on('presup')
                  ->onDelete('cascade');                  
        });
    }

    public function down()
    {
        Schema::drop('prestex');
    }
}
