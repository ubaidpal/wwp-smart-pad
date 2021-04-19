<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWindowOrderWindowProductOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('order_window_product_options', function (Blueprint $table) {
            $table->dropForeign('order_window_product_option_order_window_product_id_foreign');
        });

        Schema::table('order_window_product_options', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade')->change();
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
        Schema::table('order_window_product_options', function (Blueprint $table) {
            $table->dropForeign('order_window_product_option_order_window_product_id_foreign');
        });

        Schema::table('order_window_product_options', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->change();
        });
    }
}
