<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDealerBrandAddAccessColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('account_brands', function (Blueprint $table) {
            $table->boolean('main_admin')->default(false)->after('brand_id');
            $table->boolean('dealer_admin')->default(false)->after('brand_id');
            $table->boolean('brand_access')->default(false)->after('brand_id');
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
        Schema::table('account_brands', function (Blueprint $table) {
            $table->dropColumn('brand_access');
            $table->dropColumn('dealer_admin');
            $table->dropColumn('main_admin');
        });

    }
}
