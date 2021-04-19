<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PriceGridsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_grids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->double('price')->nullable();
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('product_option_id')->unsigned()->nullable();
            $table->foreign('product_option_id')->references('id')->on('product_options')->onUpdate('cascade')->onDelete('cascade');
            
            $table->softDeletes();
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
        Schema::dropIfExists('price_grids');
    }
}
