<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductSettingsRenameCostAdjustment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_settings', function (Blueprint $table) {
            $table->renameColumn('cost_adjustment', 'discount_received');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_settings', function (Blueprint $table) {
            $table->renameColumn('discount_received', 'cost_adjustment');
        });
    }
}
