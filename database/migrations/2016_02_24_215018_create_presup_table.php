<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresupTable extends Migration
{

    public function up()
    {
        Schema::create('presup', function (Blueprint $table) {
            $table->bigIncrements('idpre');
            $table->Integer('idpac')->unsigned();
            $table->Integer('idser')->unsigned();
            $table->smallInteger('precio')->unsigned();
            $table->tinyInteger('iva')->unsigned()->default(0);
            $table->tinyInteger('canti')->unsigned()->default(1);   
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
        Schema::drop('presup');
    }
}
