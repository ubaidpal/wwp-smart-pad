<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAppointmentStatusesUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointment_statuses', function (Blueprint $table) {
            $table->unique(['name', 'account_id'], 'appointment_statuses_per_account_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointment_statuses', function (Blueprint $table) {
            $table->dropUnique('appointment_statuses_per_account_unique');
        });
    }
}
