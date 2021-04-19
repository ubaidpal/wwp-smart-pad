<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('callable', function (Blueprint $table) {
            $table->increments('id');
            $table->string('callable_type');
            $table->index('callable_type');
            $table->integer('callable_id')->unsigned();
            $table->index('callable_id');
            $table->string('type');
            $table->string('number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('callable');
    }
}
