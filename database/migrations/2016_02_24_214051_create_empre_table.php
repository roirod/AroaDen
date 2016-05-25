<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empre', function (Blueprint $table) {
            $table->tinyInteger('id')->default(1);
            $table->string('nom',111);
            $table->string('direc',111)->nullable();
            $table->string('pobla',111)->nullable();
            $table->string('nif',22)->nullable();
            $table->string('tel1',11)->nullable();
            $table->string('tel2',11)->nullable();
            $table->string('tel3',11)->nullable();
            $table->text('notas')->nullable();
            $table->text('presutex')->nullable();
            $table->timestamps();
            $table->primary('id');     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('empre');
    }
}
