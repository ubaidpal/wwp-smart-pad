<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInstallTypesUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('install_types', function (Blueprint $table) {
            $table->unique(['type', 'account_id'], 'install_types_per_account_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('install_types', function (Blueprint $table) {
            $table->dropUnique('install_types_per_account_unique');
        });
    }
}
