<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderWindowsAddSortOrderColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_windows', function (Blueprint $table) {
            $table->tinyInteger('sort_order')->after('sell_price')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_windows', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
}
