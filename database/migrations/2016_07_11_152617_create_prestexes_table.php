<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestexesTable extends Migration
{

    public function up()
    {
        Schema::create('prestex', function (Blueprint $table) {
            $table->increments('idprestex');
            $table->Integer('idpac')->unsigned();
            $table->dateTime('cod');
            $table->text('texto')->nullable();

            $table->timestamps();

            $table->unique('cod');  

            $table->foreign('idpac')
                  ->references('idpac')->on('pacientes')
                  ->onDelete('cascade');
            $table->foreign('cod')
                  ->references('cod')->on('presup')
                  ->onDelete('cascade');                  
        });
    }

    public function down()
    {
        Schema::drop('prestex');
    }
}
