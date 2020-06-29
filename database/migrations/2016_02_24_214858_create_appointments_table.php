<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('idapp');
            $table->mediumInteger('idpat')->unsigned();
            $table->date('day');
            $table->time('hour');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['day', 'hour'], 'hoday');

            $table->foreign('idpat')
				  ->references('idpat')->on('patients')
				  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('appointments');
    }
}