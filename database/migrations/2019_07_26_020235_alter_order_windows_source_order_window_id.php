<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderWindowsSourceOrderWindowId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_windows', function(Blueprint $table) {
            $table->integer('source_order_window_id')->nullable()->unsigned();
            $table->foreign('source_order_window_id')->references('id')->on('order_windows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_windows', function(Blueprint $table) {
            $table->dropColumn('source_order_window_id');
        });
    }
}
