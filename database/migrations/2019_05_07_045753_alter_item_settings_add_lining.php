<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterItemSettingsAddLining extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_settings', function (Blueprint $table) {
            $table->boolean('lining')->default(false)->after('item_id');
            $table->integer('tax')->unsigned()->after('discount_type');
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
            $table->dropColumn('lining');
            $table->dropColumn('tax');
        });
    }
}
