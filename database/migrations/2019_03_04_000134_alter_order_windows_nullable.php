<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderWindowsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_windows', function (Blueprint $table) {
            $table->integer('quantity')->unsigned()->nullable()->change();
            $table->integer('sell_price')->unsigned()->nullable()->change();
        });
    }

    /**     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_windows', function (Blueprint $table) {
            $table->integer('quantity')->unsigned()->change();
            $table->integer('height_frac')->unsigned()->change();
            $table->integer('cost_price')->unsigned()->change();
            $table->integer('sell_price')->unsigned()->change();
        });
    }
}
