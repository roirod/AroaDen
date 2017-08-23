<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresupTable extends Migration
{

    public function up()
    {
        Schema::create('presup', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('idpre');
            $table->Integer('idpac')->unsigned();
            $table->Integer('idser')->unsigned();
            $table->smallInteger('price')->unsigned();
            $table->tinyInteger('tax')->unsigned()->default(0);
            $table->tinyInteger('units')->unsigned()->default(1);   
            $table->string('uniqid', 22);
            $table->boolean('applied')->default(0);
            $table->timestamps();
            $table->index('uniqid');
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
