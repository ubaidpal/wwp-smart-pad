<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLeadSourceReasonsUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_status_reasons', function (Blueprint $table) {
            $table->unique(['reason', 'quote_status_id'], 'quote_status_reasons_per_account_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_status_reasons', function (Blueprint $table) {
            $table->dropUnique('quote_status_reasons_per_account_unique');
        });
    }
}
