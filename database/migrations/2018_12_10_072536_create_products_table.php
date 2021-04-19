<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(false);
            $table->integer('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('minimum_width');
            $table->integer('minimum_height');
            $table->integer('price');
            $table->integer('price_rows');
            $table->boolean('is_price')->default(0); //is price for width only (boolean checkbox)
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
        Schema::dropIfExists('products');
    }
}
