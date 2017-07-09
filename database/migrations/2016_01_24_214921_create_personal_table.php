<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalTable extends Migration
{
    public function up()
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->engine = 'InnoDB';           
            $table->increments('idper');
            $table->string('surname', 111);
            $table->string('name', 111);
            $table->string('position', 66)->nullable();            
            $table->string('address', 111)->nullable();
            $table->string('city', 111)->nullable();            
            $table->string('dni', 18);
            $table->string('tel1', 18)->nullable();
            $table->string('tel2', 18)->nullable();
            $table->date('birth')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('surname');
            $table->index('name');
            $table->unique('dni');  
        });
    }

    public function down()
    {
        Schema::drop('personal');
    }
}
