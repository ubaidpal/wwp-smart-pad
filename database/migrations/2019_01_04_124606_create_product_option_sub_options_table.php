<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOptionSubOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sub_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_id')->unsigned();
            $table->foreign('option_id')->references('id')->on('product_options');
            $table->string('name')->nullable();
            $table->string('price_structure')->nullable();
            $table->integer('percent')->nullable();
            $table->integer('price')->nullable();
            $table->string('mark_default')->nullable();
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
        Schema::dropIfExists('product_sub_options');
    }
}
