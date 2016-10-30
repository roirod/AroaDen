<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactutexesTable extends Migration
{

    public function up()
    {
        Schema::create('factutex', function (Blueprint $table) {
            $table->increments('idfactex');
            $table->Integer('idpac')->unsigned();
            $table->bigInteger('factumun');
            $table->dateTime('cod');
            $table->text('texto')->nullable();

            $table->timestamps();

            $table->unique('cod');  
            $table->unique('factumun');

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
        Schema::drop('factutex');
    }
}
