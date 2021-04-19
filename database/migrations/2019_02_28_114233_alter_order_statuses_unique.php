<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderStatusesUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_statuses', function (Blueprint $table) {
            $table->unique(['name', 'account_id'], 'order_statuses_per_account_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_statuses', function (Blueprint $table) {
            $table->dropUnique('order_statuses_per_account_unique');
        });
    }
}
