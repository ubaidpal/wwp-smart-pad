<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrderWindowReportAccountId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_measure_window_reports_install_types', function(Blueprint $table) {
            $table->integer('account_id')->nullable(true)->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');
            
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_measure_window_reports_install_types', function(Blueprint $table) {
            $table->dropColumn('account_id');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropSoftDeletes();
        });
    }
}
