<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReferredByUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referred_by', function (Blueprint $table) {
            $table->unique(['referred_by', 'account_id'], 'referred_by_per_account_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referred_by', function (Blueprint $table) {
            $table->dropUnique('referred_by_per_account_unique');
        });
    }
}
