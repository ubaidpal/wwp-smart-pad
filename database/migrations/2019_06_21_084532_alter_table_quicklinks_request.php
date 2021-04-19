<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableQuicklinksRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quicklinks', function(Blueprint $table) {
            $table->string('request')->nullable();
            $table->timestamps();
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
            $table->dropColumn('request');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
