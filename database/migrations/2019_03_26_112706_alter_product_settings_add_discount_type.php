<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductSettingsAddDiscountType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_settings', function (Blueprint $table) {
            $table->string('discount_type')->after('eta_status');
            $table->integer('eta')->nullable()->change();
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
            $table->dropColumn('discount_type');
        });
    }
}
