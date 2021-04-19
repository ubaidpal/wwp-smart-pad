<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDashboardCardAddColumnHeightAndRenameSizeToWidth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashboard_cards', function (Blueprint $table) {
            $table->renameColumn('size', 'width');
            $table->integer('height')->default(3)->after('size');
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
            $table->renameColumn('width', 'size');
            $table->dropColumn('height');
        });
    }
}
