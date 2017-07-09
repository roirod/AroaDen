<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTratampacienTable extends Migration
{

    public function up()
    {
        Schema::create('tratampacien', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('idtra');
            $table->Integer('idpac')->unsigned();
            $table->Integer('idser')->unsigned();
            $table->mediumInteger('price')->unsigned();
            $table->tinyInteger('units')->unsigned()->default(1);
            $table->mediumInteger('paid')->unsigned()->default(0);
            $table->date('date');
            $table->tinyInteger('tax')->unsigned()->default(0);
            $table->tinyInteger('per1')->unsigned();
            $table->tinyInteger('per2')->unsigned();
            $table->timestamps();
            $table->index('date');
            $table->index('per1');
            $table->index('per2');
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
        Schema::drop('tratampacien');
    }
}
