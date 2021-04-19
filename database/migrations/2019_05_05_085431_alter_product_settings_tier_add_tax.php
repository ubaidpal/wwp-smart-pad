<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductSettingsTierAddTax extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_settings_tiers', function (Blueprint $table) {
            $table->integer('tax')->unsigned()->default(0)->after('price_adjustment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_settings_tiers', function (Blueprint $table) {
            $table->dropColumn('tax');
        });
    }
}
