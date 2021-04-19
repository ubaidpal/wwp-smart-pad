<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterItemSettingsAddCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_settings', function (Blueprint $table) {
            $table->integer('brand_id')->unsigned()->nullable(true)->after('id');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->integer('category_id')->unsigned()->nullable(true)->after('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_settings', function (Blueprint $table) {
            $table->dropColumn('brand_id');
            $table->dropColumn('category_id');
        });
    }
}
