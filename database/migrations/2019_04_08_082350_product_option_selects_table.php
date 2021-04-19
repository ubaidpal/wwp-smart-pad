<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductOptionSelectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_option_selects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('price_structure')->nullable();
            $table->string('price_type')->nullable();
            $table->boolean('has_quantity')->nullable();
            $table->boolean('is_discountable')->nullable();
            $table->double('value')->nullable();
            $table->tinyInteger('is_default')->nullable();
            $table->tinyInteger('order')->nullable();
            $table->Integer('option_select_id')->nullable()->default(0);
            $table->integer('product_option_id')->unsigned();
            $table->foreign('product_option_id')->references('id')->on('product_options')->onUpdate('cascade')->onDelete('cascade');
            $table->Integer('linked_option_id')->unsigned()->nullable()->default(0);
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
        Schema::dropIfExists('product_option_selects');
    }
}
