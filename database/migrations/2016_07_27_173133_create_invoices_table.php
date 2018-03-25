<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{

    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->bigIncrements('idinv');
            $table->Integer('idpat')->unsigned();
            $table->Integer('idser')->unsigned();
            $table->smallInteger('price')->unsigned();
            $table->tinyInteger('tax')->unsigned()->default(0);
            $table->tinyInteger('units')->unsigned()->default(1);
            $table->string('invoice_num', 111);
            $table->dateTime('code');
            $table->timestamps();
            $table->index('code');

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
        Schema::drop('invoices');
    }
}
