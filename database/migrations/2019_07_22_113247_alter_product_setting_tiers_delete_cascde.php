<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductSettingTiersDeleteCascde extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('product_settings_tiers', function (Blueprint $table) {
            $table->dropForeign('product_settings_tiers_product_setting_id_foreign');
        });

        Schema::table('product_settings_tiers', function (Blueprint $table) {
            $table->foreign('product_setting_id')->references('id')->on('product_settings')->onDelete('cascade')->onUpdate('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('product_settings_tiers', function (Blueprint $table) {
            $table->dropForeign('product_settings_tiers_product_setting_id_foreign');
        });

        Schema::table('product_settings_tiers', function (Blueprint $table) {
            $table->foreign('product_setting_id')->references('id')->on('product_settings')->change();
        });
    }
}
