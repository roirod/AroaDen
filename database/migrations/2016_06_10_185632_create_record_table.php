<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordTable extends Migration
{
    public function up()
    {
        Schema::create('record', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->mediumInteger('idpat')->unsigned();
            $table->text('medical_record')->nullable();
            $table->text('diseases')->nullable();
            $table->text('medicines')->nullable();
            $table->text('allergies')->nullable();
            $table->timestamps();
            $table->primary('idpat');  

            $table->foreign('idpat')
                    ->references('idpat')->on('patients')
                    ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('record');
    }
}
