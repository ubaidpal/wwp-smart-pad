<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTrackingAddOrderWindowId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_tracking', function (Blueprint $table) {
            $table->integer('order_window_id')->unsigned()->nullable(true)->after('category_id');
            $table->foreign('order_window_id')->references('id')->on('order_windows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_tracking', function (Blueprint $table) {
            $table->dropColumn('order_window_id');
        });
    }
}
