<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDashboardCardAddTabColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashboard_cards', function(Blueprint $table) {
            $table->integer('tab_id')->unsigned()->nullable()->after('is_master_console');
            $table->foreign('tab_id')->references('id')->on('dashboard_tabs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dashboard_cards', function(Blueprint $table) {
            $table->dropColumn('tab_id');
        });
    }
}
