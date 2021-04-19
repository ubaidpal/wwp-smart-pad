<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMessageLoggable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_loggable', function (Blueprint $table) {
            $table->string('custom_id')->unique()->nullable(true)->after('group');
        });

        // DB::statement('ALTER TABLE message_loggable ADD CONSTRAINT message_id_primary_key PRIMARY KEY (message_id);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_loggable', function (Blueprint $table) {
            $table->dropColumn('custom_id');

            // DB::statement('ALTER TABLE message_loggable DROP CONSTRAINT message_id_primary_key;');
        });
    }
}
