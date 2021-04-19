<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrdersAddActionsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_status_statuses', function (Blueprint $table) {
            $table->string('action')->nullable()->default('');
            $table->datetime('action_date')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_status_statuses', function (Blueprint $table) {
            $table->dropColumn('action');
            $table->dropColumn('action_date');
        });
    }
}
