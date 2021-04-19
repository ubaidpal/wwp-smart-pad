<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrdersAddSurcharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function(Blueprint $table) {
            $table->longText('surcharges_source')->nullable();
            $table->longText('surcharges_source_new')->nullable();
            $table->boolean('has_surcharges_updates')->default(0)->nullable();
        });

        Schema::table('order_window_products', function(Blueprint $table) {
            $table->boolean('has_surcharges_updates')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function(Blueprint $table) {
            $table->dropColumn('surcharges_source');
            $table->dropColumn('surcharges_source_new');
            $table->dropColumn('has_surcharges_updates');
        });
        
        Schema::table('order_window_products', function(Blueprint $table) {
            $table->dropColumn('has_surcharges_updates');
        });
    }
}
