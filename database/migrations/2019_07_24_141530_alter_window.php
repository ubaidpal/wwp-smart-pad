<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWindow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('order_window_products', function (Blueprint $table) {
            $table->dropForeign('order_window_products_product_id_foreign');
        });

        Schema::table('order_window_products', function (Blueprint $table) {
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
        Schema::table('order_window_products', function (Blueprint $table) {
            $table->dropForeign('order_window_products_product_id_foreign');
        });

        Schema::table('order_window_products', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->change();
        });
    }
}
