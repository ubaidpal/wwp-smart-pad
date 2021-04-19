<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductSettingsAndTiers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_settings', function (Blueprint $table) {
            $table->dropColumn('price_adjustment');
            $table->dropColumn('price_adjustment_type');
        });

        Schema::table('product_settings_tiers', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->integer('price_adjustment_product')->after('tier_id');
            $table->integer('price_adjustment_options')->after('price_adjustment_product');
            $table->string('price_adjustment_type')->after('price_adjustment_options');
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
            $table->integer('price_adjustment')->after('cost_adjustment');
            $table->string('price_adjustment_type')->after('price_adjustment');
        });

        Schema::table('product_settings_tiers', function (Blueprint $table) {
            $table->integer('amount')->after('tier_id');
            $table->dropColumn('price_adjustment_product');
            $table->dropColumn('price_adjustment_options');
            $table->dropColumn('price_adjustment_type');
        });
    }
}
