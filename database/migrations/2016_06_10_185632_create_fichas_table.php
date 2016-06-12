<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFichasTable extends Migration
{

    public function up()
    {
        Schema::create('ficha', function (Blueprint $table) {
            $table->Integer('idpac')->unsigned();
            $table->text('histo')->nullable();
            $table->text('enfer')->nullable();
            $table->text('medic')->nullable();
            $table->text('aler')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->primary('idpac');  

            $table->foreign('idpac')
                    ->references('idpac')->on('pacientes')
                    ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('fichas');
    }
}
