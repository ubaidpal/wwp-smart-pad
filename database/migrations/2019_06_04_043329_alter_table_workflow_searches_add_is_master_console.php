<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableWorkflowSearchesAddIsMasterConsole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workflow_searches', function(Blueprint $table) {
            $table->boolean('is_master_console')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workflow_searches', function(Blueprint $table) {
            $table->dropColumn('is_master_console');
        });
    }
}
