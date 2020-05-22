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
            $table->mediumInteger('idpat')->unsigned();
            $table->smallInteger('idser')->unsigned();
            $table->tinyInteger('units')->unsigned();            
            $table->decimal('price', 11, 2);
            $table->decimal('paid', 11, 2);
            $table->tinyInteger('tax')->unsigned();
            $table->date('day');
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
