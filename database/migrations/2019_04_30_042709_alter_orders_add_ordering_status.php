<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersAddOrderingStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('ordering_status')->default('pending')->nullable()->after('install_report_status');
            $table->string('workroom_ordering_status')->default('pending')->nullable()->after('install_report_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ordering_status');
            $table->dropColumn('workroom_ordering_status');
        });
    }
}
