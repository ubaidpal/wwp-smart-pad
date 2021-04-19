<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('product_settings', function (Blueprint $table) {
            $table->dropForeign('product_settings_product_id_foreign');
        });

        Schema::table('product_settings', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade')->change();
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
        Schema::table('product_settings', function (Blueprint $table) {
            $table->dropForeign('product_settings_product_id_foreign');
        });

        Schema::table('product_settings', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->change();
        });
    }
}
