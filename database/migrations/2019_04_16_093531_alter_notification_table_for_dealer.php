<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotificationTableForDealer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('notify_type')->nullable();
            $table->integer('country_id')->nullable()->change();
            $table->string('dealers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('notifications', function (Blueprint $table) {
            $table->integer('country_id')->change();
            $table->dropColumn('dealers');
            $table->dropColumn('notify_type');
        });
    }
}
