<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->longText('hide_on')->nullable();
            $table->tinyInteger('order')->nullable();
            $table->Integer('product_option_id')->unsigned()->nullable()->default(0);
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('constraint_id')->unsigned()->default(0);
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
        Schema::dropIfExists('product_options');
    }
}
