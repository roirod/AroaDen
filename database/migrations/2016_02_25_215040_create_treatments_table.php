<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreatmentsTable extends Migration
{
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('idtre');
            $table->Integer('idpat')->unsigned();
            $table->Integer('idser')->unsigned();
            $table->tinyInteger('units')->unsigned()->default(1);            
            $table->mediumInteger('price')->unsigned();
            $table->mediumInteger('paid')->unsigned()->default(0);
            $table->date('day');
            $table->tinyInteger('tax')->unsigned()->default(0);
            $table->timestamps();
            $table->index('day');
            $table->index('idpat');

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
        Schema::drop('treatments');
    }
}
