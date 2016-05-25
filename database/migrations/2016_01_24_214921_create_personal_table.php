<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalTable extends Migration
{

    public function up()
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->increments('idper');
            $table->string('ape', 111);
            $table->string('nom', 88);
            $table->string('cargo', 66)->nullable();
            $table->string('dni', 11);      
            $table->string('tel1', 11)->nullable();
            $table->string('tel2', 11)->nullable();    
            $table->string('direc', 111)->nullable();
            $table->string('pobla', 111)->nullable();
            $table->text('notas')->nullable();
            $table->date('fenac')->default('1950-01-01')->nullable();
            $table->timestamps();
            $table->index('ape');
            $table->index('nom');
            $table->unique('dni');
        });
    }

    public function down()
    {
        Schema::drop('personal');
    }
}
