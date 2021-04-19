<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDashboardCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashboard_cards', function (Blueprint $table) {
            $table->renameColumn('order', 'order_x');
            $table->integer('order_y')->default(0)->after('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dashboard_cards', function (Blueprint $table) {
            $table->renameColumn('order_x', 'order');
            $table->dropColumn('order_y');
        });
    }
}
