<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOptionConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_option_constraints', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('event_type');
            $table->Integer('action_type');
            $table->integer('product_option_id')->unsigned();
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
        Schema::dropIfExists('product_option_constraints');
    }
}
