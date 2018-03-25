<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTextTable extends Migration
{
    public function up()
    {
        Schema::create('budgets_text', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->increments('idbute');
            $table->Integer('idpat')->unsigned();
            $table->string('uniqid', 16);
            $table->text('text')->nullable();
            $table->timestamps();
            $table->unique('uniqid');

            $table->foreign('idpat')
                  ->references('idpat')->on('patients')
                  ->onDelete('cascade');         
        });
    }

    public function down()
    {
        Schema::drop('budgets_text');
    }
}