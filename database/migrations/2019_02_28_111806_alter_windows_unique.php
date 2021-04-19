<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWindowsUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('windows', function (Blueprint $table) {
            $table->unique(['name', 'account_id'], 'windows_per_account_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('windows', function (Blueprint $table) {
            $table->dropUnique('windows_per_account_unique');
        });
    }
}
