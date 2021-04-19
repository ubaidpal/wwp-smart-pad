<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTablesAlterProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('products', function (Blueprint $table) {
            $table->integer('unit_measurements')->nullable();
            $table->unsignedInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->longText('price_grid')->nullable();
            $table->longText('options')->nullable();
            $table->boolean('is_workroom')->nullable();
        });
        Schema::dropIfExists('product_price_grid');
        Schema::dropIfExists('product_sub_options');
        Schema::dropIfExists('product_options');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Product Table Roll Back
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('unit_measurements');
            $table->dropForeign(['products_country_id_foreign']);
            $table->dropColumn('pick_detail_id');
            $table->dropColumn('country_id');
            $table->dropColumn('price_grid');
            $table->dropColumn('options');
            $table->dropColumn('is_workroom');
        });

        //ReCreate Tables Those are Dropped
        Schema::create('product_price_grid', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('height')->nullable();
            $table->integer('width')->nullable();
            $table->integer('price')->nullable();
            $table->timestamps();
        });
        //
        Schema::create('product_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('secret')->nullable();
            $table->integer('price')->nullable();
            $table->timestamps();
        });
        //
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
}
