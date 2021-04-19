<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            //$table->integer('brand_id');
            //$table->foreign('brand_id')->references('id')->on('brands');
            $table->integer('brand_id')->unsigned();
                $table->foreign('brand_id')->references('id')->on('brands')->on_update('cascade');
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
        Schema::dropIfExists('collection');
    }
}
