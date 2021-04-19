<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_logs', function (Blueprint $table) {
            $table->dropForeign('notification_logs_account_id_foreign');
            $table->dropColumn('account_id');
        });
        Schema::table('notification_logs', function (Blueprint $table) {
            $table->string('text_color')->nullable()->default(NULL)->after('content');
            $table->string('background_color')->nullable()->default(NULL)->after('text_color');
            $table->Integer('account_id')->unsigned()->nullable()->default(NULL)->after('notification_id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('cascade');
            $table->string('notify_type')->nullable()->default(NULL)->after('background_color');
            $table->dropColumn('mark_read');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_logs', function(Blueprint $table) {
            $table->string('text_color');
            $table->string('background_color');
            $table->string('notify_type');
            $table->Integer('account_id');
            $table->dropColumn('mark_read');
        });
    }
}
