<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{

    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->bigIncrements('idfac');
            $table->Integer('idpac')->unsigned();
            $table->Integer('idser')->unsigned();
            $table->smallInteger('precio')->unsigned();
            $table->tinyInteger('iva')->unsigned()->default(0);
            $table->tinyInteger('canti')->unsigned()->default(1);   
            $table->bigInteger('factumun');
            $table->dateTime('cod');
            $table->timestamps();
            $table->index('cod');
            $table->foreign('idpac')
                      ->references('idpac')->on('pacientes')
                      ->onDelete('cascade');
            $table->foreign('idser')
                      ->references('idser')->on('servicios')
                      ->onDelete('cascade');                                        
        });
    }

    public function down()
    {
        Schema::drop('facturas');
    }
}
