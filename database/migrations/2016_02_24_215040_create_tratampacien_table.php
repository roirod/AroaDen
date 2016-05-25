<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTratampacienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tratampacien', function (Blueprint $table) {
            $table->bigIncrements('idtra');
            $table->Integer('idpac')->unsigned();
            $table->Integer('idser')->unsigned();
            $table->mediumInteger('precio')->unsigned();
            $table->tinyInteger('canti')->unsigned()->default(1);
            $table->mediumInteger('pagado')->unsigned()->default(0);
            $table->date('fecha');
            $table->tinyInteger('iva')->unsigned()->default(0);
            $table->tinyInteger('per1')->unsigned();
            $table->tinyInteger('per2')->unsigned();
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->index('fecha');
            $table->index('per1');
            $table->index('per2');
            $table->foreign('idpac')
        			->references('idpac')->on('pacientes')
        			->onDelete('cascade');
            $table->foreign('idser')
                    ->references('idser')->on('servicios')
                    ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('tratampacien');
    }
}
