<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTable extends Migration
{

    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->mediumInteger('idpat')->unsigned();
            $table->smallInteger('idser')->unsigned();
            $table->decimal('price', 11, 2);
            $table->tinyInteger('tax')->unsigned()->default(0);
            $table->tinyInteger('units')->unsigned()->default(1);   
            $table->string('uniqid', 16);
            $table->timestamps();
            $table->primary(['uniqid', 'idpat', 'idser']);

            $table->foreign('idpat')
				      ->references('idpat')->on('patients')
				      ->onDelete('cascade');
                      
            $table->foreign('idser')
                      ->references('idser')->on('services')
                      ->onDelete('cascade');                                        
        });
    }

    public function down()
    {
        Schema::drop('budgets');
    }
}
