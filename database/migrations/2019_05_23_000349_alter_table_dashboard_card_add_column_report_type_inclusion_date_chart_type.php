<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDashboardCardAddColumnReportTypeInclusionDateChartType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashboard_cards', function (Blueprint $table) {
            $table->string('report_type')->nullable()->after('icons');
            $table->string('inclusion_date')->nullable()->after('report_type');
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
            $table->dropColumn('report_type');
            $table->dropColumn('date');
        });
    }
}
