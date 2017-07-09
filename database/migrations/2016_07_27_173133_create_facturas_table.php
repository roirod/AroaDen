<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{

    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->bigIncrements('idfac');
            $table->Integer('idpac')->unsigned();
            $table->Integer('idser')->unsigned();
            $table->smallInteger('price')->unsigned();
            $table->tinyInteger('tax')->unsigned()->default(0);
            $table->tinyInteger('units')->unsigned()->default(1);   
            $table->bigInteger('invoice_number');
            $table->dateTime('code');
            $table->timestamps();
            $table->index('code');
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
