<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffPositionsEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_positions_entries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('idsta')->unsigned();
            $table->integer('idstpo')->unsigned();
            $table->index('idsta');     
            $table->index('idstpo'); 

            $table->foreign('idsta')
                    ->references('idsta')->on('staff')
                    ->onDelete('cascade');

            $table->foreign('idstpo')
                    ->references('idstpo')->on('staff_positions')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_positions_entries');
    }
}
