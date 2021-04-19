<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrdersDropCostSellPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_window_products', function (Blueprint $table) {
            $table->dropColumn('cost_price');
            $table->dropColumn('sell_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_window_products', function (Blueprint $table) {
            $table->integer('cost_price')->nullable();
            $table->integer('sell_price')->nullable();
        });
    }
}
