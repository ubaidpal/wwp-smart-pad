<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrderWindowProductOptionsAddIsFabricCuts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_window_product_options', function(Blueprint $table) {
            $table->boolean('is_fabric_cuts')->default(false)->before('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_window_product_options', function(Blueprint $table) {
            $table->dropColumn('is_fabric_cuts');
        });
    }
}
