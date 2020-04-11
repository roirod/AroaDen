<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTextTable extends Migration
{
    public function up()
    {
        Schema::create('budgets_text', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->string('uniqid', 16);
            $table->text('text')->nullable();
            $table->primary('uniqid');     
        });
    }

    public function down()
    {
        Schema::drop('budgets_text');
    }
}