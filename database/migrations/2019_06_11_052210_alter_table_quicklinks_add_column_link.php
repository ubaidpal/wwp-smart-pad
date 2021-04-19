<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableQuicklinksAddColumnLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quicklinks', function(Blueprint $table) {
            $table->string('link')->nullable()->default(null)->after('route');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quicklinks', function(Blueprint $table) {
            $table->dropColumn('link');
        });
    }
}
