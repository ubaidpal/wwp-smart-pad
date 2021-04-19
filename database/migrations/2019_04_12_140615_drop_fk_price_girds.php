<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFkPriceGirds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('price_grids', function (Blueprint $table) {
            $table->dropForeign('price_grids_product_option_id_foreign');
            $table->dropColumn('product_option_id');
        });
        Schema::table('price_grids', function (Blueprint $table) {
            $table->integer('product_sub_option_id')->unsigned()->nullable();
            $table->foreign('product_sub_option_id')->references('id')->on('product_option_selects')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('price_grids', function (Blueprint $table) {
            $table->integer('product_option_id')->unsigned()->nullable();
            $table->foreign('product_option_id')->references('id')->on('product_options')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
