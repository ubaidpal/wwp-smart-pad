<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrderWindowProductOptionsAddVersionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_window_product_options', function ($table) {
            $table->integer('version_id')->nullable()->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_window_product_options', function ($table) {
            $table->dropColumn('version_id');
        });
    }
}
