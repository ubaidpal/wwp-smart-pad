<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePricesAddOverridePrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_window_products', function (Blueprint $table) {
            $table->decimal('override_base', 10, 2)->nullable()->default(0);
            $table->decimal('override_option', 10, 2)->nullable()->default(0);
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
            $table->dropColumn('override_base');
            $table->dropColumn('override_option');
        });
    }
}
