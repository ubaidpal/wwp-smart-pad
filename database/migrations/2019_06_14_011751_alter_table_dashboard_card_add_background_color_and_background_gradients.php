<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDashboardCardAddBackgroundColorAndBackgroundGradients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashboard_cards', function(Blueprint $table) {
            $table->string('text_color')->nullable()->after('report_type');
            $table->string('background_type')->nullable()->after('report_type');
            $table->string('background_color')->nullable()->after('report_type');
            $table->string('background_gradient')->nullable()->after('report_type');
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
            $table->dropColumn('text_color');
            $table->dropColumn('background_type');
            $table->dropColumn('background_color');
            $table->dropColumn('background_gradient');
        });
    }
}
