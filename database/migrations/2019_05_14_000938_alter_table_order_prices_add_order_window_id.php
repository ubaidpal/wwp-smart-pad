<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrderPricesAddOrderWindowId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_prices', function (Blueprint $table) {
            $table->integer('order_window_id')->unsigned()->nullable();
            $table->foreign('order_window_id')->references('id')->on('order_windows')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_prices', function (Blueprint $table) {
            $table->dropColumn('order_window_id');
        });
    }
}
