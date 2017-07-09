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
            $table->engine = 'InnoDB';
            $table->bigIncrements('idcit');
            $table->integer('idpac')->unsigned();
            $table->date('day');
            $table->time('hour');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['day', 'hour'], 'horadia');
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