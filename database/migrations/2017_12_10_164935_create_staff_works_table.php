<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_works', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('idstwo');
            $table->Integer('idsta')->unsigned();
            $table->bigInteger('idtre')->unsigned();
            $table->index('idtre');     
            $table->index('idsta'); 

            $table->foreign('idsta')
                    ->references('idsta')->on('staff')
                    ->onDelete('cascade');

            $table->foreign('idtre')
                    ->references('idtre')->on('treatments')
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
    }
}
