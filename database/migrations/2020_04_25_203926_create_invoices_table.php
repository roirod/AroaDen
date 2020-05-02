<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('number');
            $table->year('serial');
            $table->mediumInteger('idpat')->unsigned();
            $table->char('type', 16);
            $table->Integer('parent_num')->unsigned()->nullable();
            $table->year('parent_serial')->nullable();
            $table->date('exp_date');
            $table->string('no_tax', 5);
            $table->text('notes')->nullable();
            $table->index('idpat');
            $table->index('exp_date');

            $table->foreign('idpat')
                    ->references('idpat')->on('patients')
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
        Schema::dropIfExists('invoices');
    }
}
