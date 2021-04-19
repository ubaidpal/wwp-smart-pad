<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderMeasureWindowReportsFeedbackNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_measure_window_reports', function (Blueprint $table) {
            $table->string('feedback_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_measure_window_reports', function (Blueprint $table) {
            $table->dropColumn('feedback_notes');
        });
    }
}
