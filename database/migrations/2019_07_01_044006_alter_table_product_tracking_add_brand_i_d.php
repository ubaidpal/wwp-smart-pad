<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProductTrackingAddBrandID extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_tracking', function (Blueprint $table) {
            $table->integer('brand_id')->unsigned()->nullable(true)->after('order_id');
            $table->foreign('brand_id')->references('id')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_tracking', function (Blueprint $table) {
            $table->dropColoumn('brand_id');
        });
    }
}
