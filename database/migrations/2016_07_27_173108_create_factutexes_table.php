<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactutexesTable extends Migration
{
    public function up()
    {
        Schema::create('factutex', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->increments('idfactex');
            $table->Integer('idpac')->unsigned();
            $table->bigInteger('invoice_number');
            $table->dateTime('code');
            $table->text('text')->nullable();
            $table->timestamps();
            $table->unique('code');  
            $table->unique('invoice_number');

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
        Schema::drop('factutex');
    }
}
