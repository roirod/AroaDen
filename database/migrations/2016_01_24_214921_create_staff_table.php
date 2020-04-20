<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->engine = 'InnoDB';           
            $table->increments('idsta');
            $table->string('surname', 111);
            $table->string('name', 111);
            $table->string('address', 111)->nullable()->default(' ');
            $table->string('city', 111)->nullable()->default(' ');
            $table->string('dni', 18);
            $table->string('tel1', 18)->nullable()->default(' ');
            $table->string('tel2', 18)->nullable()->default(' ');
            $table->date('birth')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('surname');
            $table->index('name');
            $table->unique('dni');
        });
    }

    public function down()
    {
        Schema::drop('staff');
    }
}
