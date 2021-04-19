<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLeadSourcesUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_statuses', function (Blueprint $table) {
            $table->unique(['name', 'account_id'], 'quote_statuses_per_account_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_statuses', function (Blueprint $table) {
            $table->dropUnique('quote_statuses_per_account_unique');
        });
    }
}
