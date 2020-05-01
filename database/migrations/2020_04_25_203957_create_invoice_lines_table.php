<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('idinli');
            $table->Integer('number')->unsigned();
            $table->bigInteger('idtre')->unsigned();
            $table->smallInteger('idser')->unsigned();
            $table->tinyInteger('units')->unsigned();            
            $table->mediumInteger('price')->unsigned();
            $table->mediumInteger('paid')->unsigned();
            $table->tinyInteger('tax')->unsigned();
            $table->date('day');
            $table->index('number');

            $table->foreign('number')
                    ->references('number')->on('invoices')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_lines');
    }
}
