<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->engine = 'InnoDB';    
            $table->bigIncrements('idfiles');
            $table->Integer('iduser')->unsigned();
            $table->string('type', 22);
            $table->text('info');
            $table->string('originalName', 55);
            $table->index('iduser', 'type');
            $table->index('originalName');
        });
    }

    public function down()
    {
        Schema::drop('files');
    }
}
