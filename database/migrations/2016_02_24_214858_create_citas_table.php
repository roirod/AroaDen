<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->bigIncrements('idcit');
            $table->integer('idpac')->unsigned();
            $table->date('diacit');
            $table->time('horacit');
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->index(['diacit', 'horacit'], 'horadia');
            $table->foreign('idpac')
				  ->references('idpac')->on('pacientes')
				  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('citas');
    }
}
